<!-- Stored in resources/views/layouts/admin/partials/top_bar_navigation.blade.php -->

@extends('layouts.admin.master')

@section('title', 'FreePlus-TosAdmin')

@section('content')
<div class="container">
    <div class="card bg-light mt-3">
        <div class="card-header">
            Import product Excel or CSV to Database 
        </div>
        <div class="card-body">
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success">Import  Data</button>
            </form>
        </div>
    </div>

</div>
@endsection
