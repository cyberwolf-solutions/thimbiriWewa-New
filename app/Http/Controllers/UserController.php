<?php

namespace App\Http\Controllers;

use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Users';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        $data = User::all();
        return view('users.index', compact('title', 'breadcrumbs', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $title = 'Users';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => route('users.index'), 'active' => false],
            ['label' => 'Create', 'url' => '', 'active' => true],
        ];
        $roles = Role::all();

        $is_edit = false;

        return view('users.create-edit', compact('title', 'breadcrumbs', 'roles', 'is_edit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_by' => Auth::user()->id
            ];

            $user = User::create($data);

            $role = Role::find($request->role);

            $user->assignRole($role);

            $email = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ];

            SendWelcomeEmail::dispatch($email)->delay(now()->addSeconds(60));

            return json_encode(['success' => true, 'message' => 'User created', 'url' => route('users.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $title = 'Users';

        $breadcrumbs = [
            ['label' => $title, 'url' => route('users.index'), 'active' => false],
            ['label' => 'Edit', 'url' => '', 'active' => true],
        ];

        $data = User::find($id);

        $roles = Role::all();

        $is_edit = true;

        return view('users.create-edit', compact('title', 'breadcrumbs', 'data', 'roles', 'is_edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'updated_by' => Auth::user()->id
            ];

            $user = User::find($id);
            $user->update($data);

            $role = Role::find($request->role);

            $user->syncRoles($role);

            return json_encode(['success' => true, 'message' => 'User updated', 'url' => route('users.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }

    /**
     * Change Dark Mode for Specific User.
     */
    public function changeMode() {
        try {
            if (Auth::user()) {
                $id = Auth::user()->id;
                $user = User::find($id);
                if (Auth::user()->is_dark == 1) {
                    $user->is_dark = 0;
                } else {
                    $user->is_dark = 1;
                }
                $user->save();
                return json_encode(['success' => true, 'message' => 'Theme changed!']);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }

    /**
     * Change Active Status for Specific User.
     */
    public function status(Request $request) {
        $id = $request->id;
        $status = $request->status;
        try {
            $user = User::find($id);
            $user->is_active = !$status;
            $user->save();
            return json_encode(['success' => true, 'message' => 'User status updated', 'url' => route('users.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    /**
     * Reset Password for Specific User.
     */
    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {
            $user = User::find($request->id);

            // Update the user's password
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            return json_encode(['success' => true, 'message' => 'User password changed', 'url' => route('users.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }

    public function profile() {
        return view('auth.profile');
    }

    public function profileUpdate(Request $request) {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            User::find(Auth::user()->id)->update($data);

            return json_encode(['success' => true, 'message' => 'Profile updated', 'url' => route('profile')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }
    public function imageUpdate(Request $request) {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {
            // Handle file upload for image
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Generate a unique filename based on date and other details
                $fileName = sprintf(
                    'avatar_%s_%s.%s', // Use placeholders for user name, date/time, and file extension
                    str_replace(' ', '_', Auth::user()->name), // Replace spaces in username with underscores
                    now()->format('YmdHis'), // Use the current date and time for uniqueness
                    $file->getClientOriginalExtension() // Get the original file extension
                );


                // Store the file with the custom name
                $filePath = $file->storeAs('avatars', $fileName, 'public');

                // Add the file path to the $data array
                $data['avatar'] = $filePath;
            }

            User::find(Auth::user()->id)->update($data);

            return json_encode(['success' => true, 'message' => 'Image updated', 'url' => route('profile')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }
    public function coverUpdate(Request $request) {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {
            // Handle file upload for image
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Generate a unique filename based on date and other details
                $fileName = sprintf(
                    '%s_%s.%s', // Use placeholders for user name, date/time, and file extension
                    str_replace(' ', '_', Auth::user()->name), // Replace spaces in username with underscores
                    now()->format('YmdHis'), // Use the current date and time for uniqueness
                    $file->getClientOriginalExtension() // Get the original file extension
                );

                // Store the file with the custom name
                $filePath = $file->storeAs('covers', $fileName, 'public');

                // Add the file path to the $data array
                $data['cover'] = $filePath;
            }

            User::find(Auth::user()->id)->update($data);

            return json_encode(['success' => true, 'message' => 'Cover updated', 'url' => route('profile')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }
    public function passwordUpdate(Request $request) {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'oldpasswordInput' => 'required', // Adjust the minimum length as needed
            'newpasswordInput' => 'required|min:8|confirmed', // 'confirmed' ensures that newpasswordInput_confirmation field is present and matches newpasswordInput
        ]);
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {

            if (!Hash::check($request->oldpasswordInput, Auth::user()->password)) {
                return response()->json(['success' => false, 'message' => 'Old Password Doesn\'t match!']);
            }

            $data = [
                'password' => Hash::make($request->newpasswordinput)
            ];

            User::find(Auth::user()->id)->update($data);

            return json_encode(['success' => true, 'message' => 'Password changed', 'url' => route('profile')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }


    public function userReport(){
        $title = 'Users Report';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];
        // $data = User::all();
        $data = User::with('roles')->get();

        return view ('reports.UserReports' , compact('data'));
    }
}
