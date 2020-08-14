@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Contacts</h2>
            </div>
            <div class="pull-right">
            <form action="{{ route('contact.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="custom-file">
                    <input type="file" class="btn btn-primary" name="file" id="validatedCustomFile" required>

                </div>
                <button type="submit" class="btn btn-primary">Import data</button>
            </form>
            </div>

            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('contact.track') }}">Track</a>

                <a class="btn btn-success" href="{{ route('contact.create') }}"> Create New Contact</a>
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>email</th>
            <th>phone number</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($contact as $cont)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $cont->first_name }}</td>
                <td>{{ $cont->email }}</td>
                <td>{{ $cont->phone_number }}</td>
                <td>
                    <form action="{{ route('contact.destroy',$cont->id) }}" method="POST">

                        <a class="btn btn-info" href="{{ route('contact.show',$cont->id) }}">Show</a>

                        <a class="btn btn-primary" href="{{ route('contact.edit',$cont->id) }}">Edit</a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $contact->links() !!}

    <br>

@endsection
