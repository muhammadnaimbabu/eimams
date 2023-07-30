<?php



?>
<h2>SMTP Server settings</h2>
<form id="az_smtp_form" action="<?php get_the_permalink() ?>" method="post"><br><br>
    <div class="az_input">
        <label for="host">Host</label>
        <input type="text" name="host">
    </div>
    <div class="az_input">
        <label for="port">Port</label>
        <input type="text" name="port">
    </div>
    <div class="az_input">
        <label for="username">Username</label>
        <input type="text" name="username">
    </div>
    <div class="az_input">
        <label for="password">Password</label>
        <input type="password" name="password">
    </div>
    <div class="az_input">
        <input id="az_smtp_form_submit" type="submit" name="submit" value="Save">
    </div>
</form>

<style>
    #az_smtp_form {
        display: flex;
        flex-direction: column;
        margin-top: -20px;
    }

    #az_smtp_form .az_input {
        margin: 10px 0px;
        display: grid;
    }

    #az_smtp_form .az_input input {
        width: 20%;
    }
    #az_smtp_form_submit{
        padding: 10px 0px;
        color: white;
        background-color: #62CDFF;
        cursor: pointer;
        border: none;
        border-radius: 5px;
    }
</style>