<div class="container">
    <!-- Content -->
    <div class="row">
        <div class="skip-1 col-10 header">
            <h2>Subscribe to newsletter</h2>
        </div>
    </div>
    <div class="row">
        <div class="skip-1 col-10">
            Subscribe to our newsletter and get 10% discount on pineapple glasses.
        </div>
    </div>
</div>
<form method="post" action="?section=subscribe">
    <div class="row subscribe">
        <div class="col-12 col-sm-12 subscribe-input form-control">
            <input type="text" placeholder="Type your email address here…" name="email"
                   autocomplete="off" value="<?=$values['email']??'';?>"/>
            <input type="submit" value=" " disabled="disabled"/>
            <?php
            foreach($errors['email']??[] as $error) {
                echo "<div class='error'>$error</div>";
            }
            ?>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-10 skip-1 form-control">
                <input type="checkbox" id="terms" name="terms" value="1" <?php echo ($values['terms']??false)?'checked':''; ?>/>
                <label for="terms">I agree to <a href="#">terms of service</a></label>
                <?php
                foreach($errors['terms']??[] as $error) {
                    echo "<div class='error'>$error</div>";
                }
                ?>
            </div>
        </div>
    </div>
</form>