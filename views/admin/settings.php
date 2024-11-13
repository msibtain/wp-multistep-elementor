<div class="wrap">
    <h1>Stripe Settings</h1>

    <?php
    if ($_POST)
    {
        update_option("ilab_stripe_key", $_POST['ilab_stripe_key']);
        update_option("ilab_stripe_secret", $_POST['ilab_stripe_secret']);
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Stripe Settings updated!', 'ilab' ); ?></p>
        </div>
        <?php
    }
    ?>

    <form method="post" >
        <table class="form-table">
        <tr>
            <th scope="row"><label for="blogname">Stripe Key</label></th>
            <td>
                <input name="ilab_stripe_key" type="text" id="ilab_stripe_key" value="<?php echo get_option("ilab_stripe_key"); ?>" class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="blogname">Stripe Secret</label></th>
            <td>
                <input name="ilab_stripe_secret" type="text" id="ilab_stripe_secret" value="<?php echo get_option("ilab_stripe_secret"); ?>" class="regular-text">
            </td>
        </tr>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>

    </form>
</div>