<form method="post" action="{{route('login')}}">
    @CSRF()
    <div className="col-sm-6 offset-sm-3" style=" border:1px solid white;">
        <h1>Login</h1>
        <input type="text" name="email" placeholder="Your Email Here.." class="form-control"  />
        <br />
        <input
            type="password" name="password" placeholder="Your Password Here.." class="form-control"/>
        <br />
        <input type="submit" value="Login" class="btn btn-primary" />
    </div>
</form>
