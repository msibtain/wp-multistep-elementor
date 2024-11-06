<form id="payment-form">

    <label>First Name</label>
    <br>
    <input type="text" readonly name="fname" id="fname" value="<?php echo $_GET['fname'] ?>" />
    <br><br>

    <label>Last Name</label>
    <br>
    <input type="text" readonly name="lname" id="lname" value="<?php echo $_GET['lname'] ?>" />
    <br><br>

    <label>Email</label>
    <br>
    <input type="text" readonly name="email" id="email" value="<?php echo $_GET['email'] ?>" />
    <br><br>

    <label>Password</label>
    <br>
    <input type="password"  name="password" id="password"  />
    <br><br>


    <label>Card Details</label>
    <div id="card-element">Loading payment form...</div>

    <div align="center">
        <button type="submit" style="background:#004360;">Pay $1</button>
    </div>
    

    <div id="card-errors" role="alert"></div>

</form>