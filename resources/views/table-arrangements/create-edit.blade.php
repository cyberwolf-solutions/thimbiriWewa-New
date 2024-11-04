@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-sm-0">{{ $title }}</h3>

                    <ol class="breadcrumb m-0 mt-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

                        @foreach ($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active' : '' }}">
                                @if (!$breadcrumb['active'])
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                                @else
                                    {{ $breadcrumb['label'] }}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div class="page-title-right">
                    {{-- Add Buttons Here --}}
                    {{-- <a href="{{ route('tables.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                        title="Create">
                        <i class="ri-add-line fs-5"></i>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" class="ajax-form"
                    action="{{ $is_edit ? route('table-arrangements.update', $data->id) : route('table-arrangements.store') }}">
                    @csrf
                    @if ($is_edit)
                        @method('PATCH')
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" id="" class="form-control"
                                value="{{ $is_edit ? $data->name : '' }}" placeholder="Enter Name" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id=""
                                      rows="1" placeholder="Enter Description">{{ $is_edit ? $data->description : '' }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="btn-group">
                            <button class="btn btn-primary rectangle">+ &#9647; Table</button>
                            <button class="btn btn-primary circle">+ &#9711; Table</button>
                            <button class="btn btn-primary triangle">+ &#9651; Table</button>
                            <button class="btn btn-primary chair">+ Chair</button>
                            <button class="btn btn-primary bar">+ Bar</button>
                            <button class="btn btn-info wall">+ Wall</button>
                            <button class="btn btn-danger remove">Remove</button>
                            <button class="btn btn-warning customer-mode">Customer mode</button>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <canvas id="canvas" width="700" height="500"></canvas>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2"
                                onclick="window.location='{{ route('table-arrangements.index') }}'">Cancel</button>
                            <button class="btn btn-primary">{{ $is_edit ? 'Update' : 'Create' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let canvas
        let number
        const grid = 30
        const backgroundColor = '#AAAAAA'
        const lineStroke = '#ebebeb'
        const tableFill = 'rgba(150, 111, 51, 0.7)'
        const tableStroke = '#694d23'
        const tableShadow = 'rgba(0, 0, 0, 0.4) 3px 3px 7px'
        const chairFill = 'rgba(67, 42, 4, 0.7)'
        const chairStroke = '#32230b'
        const chairShadow = 'rgba(0, 0, 0, 0.4) 3px 3px 7px'
        const barFill = 'rgba(0, 93, 127, 0.7)'
        const barStroke = '#003e54'
        const barShadow = 'rgba(0, 0, 0, 0.4) 3px 3px 7px'
        const barText = 'Bar'
        const wallFill = 'rgba(136, 136, 136, 0.7)'
        const wallStroke = '#686868'
        const wallShadow = 'rgba(0, 0, 0, 0.4) 5px 5px 20px'

        let widthEl = 700
        let heightEl = 500
        let canvasEl = document.getElementById('canvas')

        function initCanvas() {
            if (canvas) {
                canvas.clear()
                canvas.dispose()
            }

            canvas = new fabric.Canvas('canvas')
            number = 1
            canvas.backgroundColor = backgroundColor

            for (let i = 0; i < (canvas.height / grid); i++) {
                const lineX = new fabric.Line([ 0, i * grid, canvas.height, i * grid], {
                    stroke: lineStroke,
                    selectable: false,
                    type: 'line'
                })
                const lineY = new fabric.Line([ i * grid, 0, i * grid, canvas.height], {
                    stroke: lineStroke,
                    selectable: false,
                    type: 'line'
                })
                sendLinesToBack()
                canvas.add(lineX)
                canvas.add(lineY)
            }

            canvas.on('object:moving', function(e) {
                snapToGrid(e.target)
            })

            canvas.on('object:scaling', function(e) {
                if (e.target.scaleX > 5) {
                    e.target.scaleX = 5
                }
                if (e.target.scaleY > 5) {
                    e.target.scaleY = 5
                }
                if (!e.target.strokeWidthUnscaled && e.target.strokeWidth) {
                    e.target.strokeWidthUnscaled = e.target.strokeWidth
                }
                if (e.target.strokeWidthUnscaled) {
                    e.target.strokeWidth = e.target.strokeWidthUnscaled / e.target.scaleX
                    if (e.target.strokeWidth === e.target.strokeWidthUnscaled) {
                        e.target.strokeWidth = e.target.strokeWidthUnscaled / e.target.scaleY
                    }
                }
            })

            canvas.on('object:modified', function(e) {
                e.target.scaleX = e.target.scaleX >= 0.25 ? (Math.round(e.target.scaleX * 2) / 2) : 0.5
                e.target.scaleY = e.target.scaleY >= 0.25 ? (Math.round(e.target.scaleY * 2) / 2) : 0.5
                snapToGrid(e.target)
                if (e.target.type === 'table') {
                    canvas.bringToFront(e.target)
                }
                else {
                    canvas.sendToBack(e.target)
                }
                sendLinesToBack()
            })

            canvas.on('object:moving', function (e) {
                checkBoundingBox(e)
            })
            canvas.on('object:rotating', function (e) {
                checkBoundingBox(e)
            })
            canvas.on('object:scaling', function (e) {
                checkBoundingBox(e)
            })
        }
        initCanvas()

        function resizeCanvas() {
            widthEl = 700
            heightEl = 500
            canvasEl.width = widthEl.value ? widthEl.value : 700
            canvasEl.height = heightEl.value ? heightEl.value : 500
            const canvasContainerEl = document.querySelectorAll('.canvas-container')[0]
            canvasContainerEl.style.width = canvasEl.width
            canvasContainerEl.style.height = canvasEl.height
        }
        resizeCanvas()

        function generateId() {
            return Math.random().toString(36).substr(2, 8)
        }

        function addRect(left, top, width, height) {
            const id = generateId()
            const o = new fabric.Rect({
                width: width,
                height: height,
                fill: tableFill,
                stroke: tableStroke,
                strokeWidth: 2,
                shadow: tableShadow,
                originX: 'center',
                originY: 'center',
                centeredRotation: true,
                snapAngle: 45,
                selectable: true
            })
            const t = new fabric.IText(number.toString(), {
                fontFamily: 'Calibri',
                fontSize: 14,
                fill: '#fff',
                textAlign: 'center',
                originX: 'center',
                originY: 'center'
            })
            const g = new fabric.Group([o, t], {
                left: left,
                top: top,
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'table',
                id: id,
                number: number
            })
            canvas.add(g)
            number++
            return g
        }

        function addCircle(left, top, radius) {
            const id = generateId()
            const o = new fabric.Circle({
                radius: radius,
                fill: tableFill,
                stroke: tableStroke,
                strokeWidth: 2,
                shadow: tableShadow,
                originX: 'center',
                originY: 'center',
                centeredRotation: true
            })
            const t = new fabric.IText(number.toString(), {
                fontFamily: 'Calibri',
                fontSize: 14,
                fill: '#fff',
                textAlign: 'center',
                originX: 'center',
                originY: 'center'
            })
            const g = new fabric.Group([o, t], {
                left: left,
                top: top,
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'table',
                id: id,
                number: number
            })
            canvas.add(g)
            number++
            return g
        }

        function addTriangle(left, top, radius) {
            const id = generateId()
            const o = new fabric.Triangle({
                radius: radius,
                fill: tableFill,
                stroke: tableStroke,
                strokeWidth: 2,
                shadow: tableShadow,
                originX: 'center',
                originY: 'center',
                centeredRotation: true
            })
            const t = new fabric.IText(number.toString(), {
                fontFamily: 'Calibri',
                fontSize: 14,
                fill: '#fff',
                textAlign: 'center',
                originX: 'center',
                originY: 'center'
            })
            const g = new fabric.Group([o, t], {
                left: left,
                top: top,
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'table',
                id: id,
                number: number
            })
            canvas.add(g)
            number++
            return g
        }

        function addChair(left, top, width, height) {
            const o = new fabric.Rect({
                left: left,
                top: top,
                width: 30,
                height: 30,
                fill: chairFill,
                stroke: chairStroke,
                strokeWidth: 2,
                shadow: chairShadow,
                originX: 'left',
                originY: 'top',
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'chair',
                id: generateId()
            })
            canvas.add(o)
            return o
        }

        function addBar(left, top, width, height) {
            const o = new fabric.Rect({
                width: width,
                height: height,
                fill: barFill,
                stroke: barStroke,
                strokeWidth: 2,
                shadow: barShadow,
                originX: 'center',
                originY: 'center',
                type: 'bar',
                id: generateId()
            })
            const t = new fabric.IText(barText, {
                fontFamily: 'Calibri',
                fontSize: 14,
                fill: '#fff',
                textAlign: 'center',
                originX: 'center',
                originY: 'center'
            })
            const g = new fabric.Group([o, t], {
                left: left,
                top: top,
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'bar'
            })
            canvas.add(g)
            return g
        }

        function addWall(left, top, width, height) {
            const o = new fabric.Rect({
                left: left,
                top: top,
                width: width,
                height: height,
                fill: wallFill,
                stroke: wallStroke,
                strokeWidth: 2,
                shadow: wallShadow,
                originX: 'left',
                originY: 'top',
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'wall',
                id: generateId()
            })
            canvas.add(o)
            return o
        }

        function snapToGrid(target) {
            target.set({
                left: Math.round(target.left / (grid / 2)) * grid / 2,
                top: Math.round(target.top / (grid / 2)) * grid / 2
            })
        }

        function checkBoundingBox(e) {
            const obj = e.target

            if (!obj) {
                return
            }
            obj.setCoords()

            const objBoundingBox = obj.getBoundingRect()
            if (objBoundingBox.top < 0) {
                obj.set('top', 0)
                obj.setCoords()
            }
            if (objBoundingBox.left > canvas.width - objBoundingBox.width) {
                obj.set('left', canvas.width - objBoundingBox.width)
                obj.setCoords()
            }
            if (objBoundingBox.top > canvas.height - objBoundingBox.height) {
                obj.set('top', canvas.height - objBoundingBox.height)
                obj.setCoords()
            }
            if (objBoundingBox.left < 0) {
                obj.set('left', 0)
                obj.setCoords()
            }
        }

        function sendLinesToBack() {
            canvas.getObjects().map(o => {
                if (o.type === 'line') {
                    canvas.sendToBack(o)
                }
            })
        }

        document.querySelectorAll('.rectangle')[0].addEventListener('click', function() {
            const o = addRect(0, 0, 60, 60)
            canvas.setActiveObject(o)
        })

        document.querySelectorAll('.circle')[0].addEventListener('click', function() {
            const o = addCircle(0, 0, 30)
            canvas.setActiveObject(o)
        })

        document.querySelectorAll('.triangle')[0].addEventListener('click', function() {
            const o = addTriangle(0, 0, 30)
            canvas.setActiveObject(o)
        })

        document.querySelectorAll('.chair')[0].addEventListener('click', function() {
            const o = addChair(0, 0)
            canvas.setActiveObject(o)
        })

        document.querySelectorAll('.bar')[0].addEventListener('click', function() {
            const o = addBar(0, 0, 180, 60)
            canvas.setActiveObject(o)
        })

        document.querySelectorAll('.wall')[0].addEventListener('click', function() {
            const o = addWall(0, 0, 60, 180)
            canvas.setActiveObject(o)
        })

        document.querySelectorAll('.remove')[0].addEventListener('click', function() {
            const o = canvas.getActiveObject()
            if (o) {
                o.remove()
                canvas.remove(o)
                canvas.discardActiveObject()
                canvas.renderAll()
            }
        })

        document.querySelectorAll('.customer-mode')[0].addEventListener('click', function() {
            canvas.getObjects().map(o => {
                o.hasControls = false
                o.lockMovementX = true
                o.lockMovementY = true
                if (o.type === 'chair' || o.type === 'bar' || o.type === 'wall') {
                    o.selectable = false
                }
                o.borderColor = '#38A62E'
                o.borderScaleFactor = 2.5
            })
            canvas.selection = false
            canvas.hoverCursor = 'pointer'
            canvas.discardActiveObject()
            canvas.renderAll()
            document.querySelectorAll('.admin-menu')[0].style.display = 'none'
            document.querySelectorAll('.customer-menu')[0].style.display = 'block'
        })

        function formatTime(val) {
            const hours =  Math.floor(val / 60)
            const minutes = val % 60
            const englishHours = hours > 12 ? hours - 12 : hours

            const normal = hours + ':' + minutes + (minutes === 0 ? '0' : '')
            const english = englishHours + ':' + minutes + (minutes === 0 ? '0' : '') + ' ' + (hours > 12 ? 'PM' : 'AM')

            return normal + ' (' + english + ')'
        }

        function addDefaultObjects() {
            addChair(15, 105)
            addChair(15, 135)
            addChair(75, 105)
            addChair(75, 135)
            addChair(225, 75)
            addChair(255, 75)
            addChair(225, 135)
            addChair(255, 135)
            addChair(225, 195)
            addChair(255, 195)
            addChair(225, 255)
            addChair(255, 255)
            addChair(15, 195)
            addChair(45, 195)
            addChair(15, 255)
            addChair(45, 255)
            addChair(15, 315)
            addChair(45, 315)
            addChair(15, 375)
            addChair(45, 375)
            addChair(225, 315)
            addChair(255, 315)
            addChair(225, 375)
            addChair(255, 375)
            addChair(15, 435)
            addChair(15, 495)
            addChair(15, 555)
            addChair(15, 615)
            addChair(225, 615)
            addChair(255, 615)
            addChair(195, 495)
            addChair(195, 525)
            addChair(255, 495)
            addChair(255, 525)
            addChair(225, 675)
            addChair(255, 675)

            addRect(30, 90, 60, 90)
            addRect(210, 90, 90, 60)
            addRect(210, 210, 90, 60)
            addRect(0, 210, 90, 60)
            addRect(0, 330, 90, 60)
            addRect(210, 330, 90, 60)
            addRect(0, 450, 60, 60)
            addRect(0, 570, 60, 60)
            addRect(210, 480, 60, 90)
            addRect(210, 630, 90, 60)

            addBar(120, 0, 180, 60)

            addWall(120, 510, 60, 60)
        }
        //addDefaultObjects()
    </script>
@endsection
