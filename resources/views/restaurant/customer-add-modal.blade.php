<div class="modal-body">
    <div class="row">
        <form action="{{ route('customers.store') }}" class="ajax-form" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12 mb-3 required">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Name">
                </div>
                <div class="col-md-6 mb-3 required">
                    <label for="">Contact</label>
                    <input type="text" class="form-control" name="contact" placeholder="Phone">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Email">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="">Address</label>
                    <textarea name="address" id="" cols="30" rows="2" class="form-control"></textarea>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col text-end">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
        <div class="row">
            <a href="javascript:void(0)" class="link-info" data-ajax-popup="true" data-title="Customers" data-size="lg"
                data-url="{{ route('restaurant.customer') }}">Need to select a customer? Click
                Here!</a>
        </div>
    </div>
</div>
