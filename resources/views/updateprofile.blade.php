@extends('layouts.navbar')

@section('content') 

    <div class="container w-50 m-auto">
        <h1>Update Profile</h1>
    <form action="{{url('updateprofile')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="name">Name:</label>
    <input type="text" id="name" value="{{$user->name}}" name="name">
    <br>
    <label for="email">Eamil:</label>
    <input type="text" id="email" value="{{$user->email}}" name="email">
    <br>
    <label for="city">City:</label>
    <input type="text" id="city" value="{{$user->city}}" name="city">
    <br>
    <label for="profilepic">Profile pic:</label>
    <img src="{{$user->profilepic}}" alt="profile pic" width="50%">
    <br>
    <input type="file" name="file" id="profilepic" >
    <br>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
    @endsection 
