@extends('layouts.main')

@section('container')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <main class="form-signin mt-4">
                <form action="" method="post">
                    @csrf
                    @if (session()->has('success'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></
                                    button>
                        </div>
                    @endif
                    <h1 class="h3 mb-3 fw-normal text-center">Registration</h1>
                    <div class="form-floating">
                        <input type="name" name="name" class="form-control" id="name" placeholder="name@example.com"
                            autocomplete="off" required>
                        <label for="name">Name</label>
                    </div>
                    <div class="form-floating">
                        <input type="username" name="username" class="form-control" id="username"
                            placeholder="name@example.com" autocomplete="off" required>
                        <label for="username">username</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com"
                            autocomplete="off" required>
                        <label for="email">Email address</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password"
                            autocomplete="off" required>
                        <label for="password">Password</label>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>
                </form>
                <small class="d-block text-center mt-2">Already Registered? <a href="/login">Login</a></small>
            </main>

        </div>
    </div>
@endsection
