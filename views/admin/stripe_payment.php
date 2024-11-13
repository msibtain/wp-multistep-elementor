<?php
$user_id = $_GET['user_id'];
$objUser = get_user_by("ID", $user_id);
$strCustomerID = get_user_meta($user_id, "stripe_customer_id", true);
$strPaymentMethodID = get_user_meta($user_id, "stripe_payment_method", true);

?>
<div class="wrap">
    <h1>Stripe Payment for user - "<?php echo $objUser->first_name; ?> <?php echo $objUser->last_name; ?>"</h1>

    <?php
    if ($strCustomerID)
    {
        if ($_POST)
        {
            global $ilabAPI;
            $response = $ilabAPI->stripe_customer_charge( $_POST['amount'], $strCustomerID, $strPaymentMethodID )
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo $response; ?></p>
            </div>
            <?php
        }

        ?>
        <form method="post" >
            <table class="form-table">
            <tr>
                <th scope="row"><label for="blogname">Enter Amount</label></th>
                <td>
                    <input name="amount" type="text" id="amount" class="regular-text">
                </td>
            </tr>

            
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Pay Now"></p>

        </form>
        <?php
    }
    else
    {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>This user has no Stripe Customer ID.</p>
        </div>
        <?php
    }
    
    ?>
</div>