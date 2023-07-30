<?php
    require_once("kv-paypal.class.refund.php");

    /**
     *
     * @author Mubashir Ali
     * saad_ali6@yahoo.com
     * @copyright GNU
     * @example example.php
     * @filesource class.refund.php
     * @version 1.0
     *
     * This PayPal API provides the functionality of Refunding Amount.
     * Credentials are omitted from here for privacy purpose. To use it credentials are compulsory to provide.
     */

    /*
     * Currency Types
     * ('USD', 'GBP', 'EUR', 'JPY', 'CAD', 'AUD')
     *
     * Refund Type
     * ('Partial', 'Full')
     *
     * Transaction ID
     * We can get the Transaction ID from IPN Response
     */

    if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_form'])) {
        global $wpdb; 
        $job_seeker_table = $wpdb->prefix.'jbs_subactive';
        if(isset($_GET['txn_id'])){
            $txn_id =$_GET['txn_id']; 
            if($txn_id!=0){
                $txn_pack = $wpdb->get_row("SELECT * FROM " . $job_seeker_table . " WHERE id =".$txn_id , ARRAY_A);
                print_r( $aryData);

                $aryData['transactionID'] =  $txn_pack['txn_id'];
                $aryData['refundType'] = "Full"; //Partial or Full
                $aryData['currencyCode'] = "GBP";
                $aryData['amount'] = $txn_pack['amount']- ($txn_pack['amount']*0.04);
                $aryData['memo'] = "There Memo Detail entered for Full Refund";
                $aryData['invoiceID'] = "";
               // print_r($aryData);
                $ref = new PayPalRefund("live");
                $aryRes = $ref->refundAmount($aryData);
                
                if($aryRes['ACK'] == "Success"){                    
                   $wpdb->update( $job_seeker_table,
                    array(  
                        'end_date'          =>  date('Y-m-d'),
                        'per_post'          =>  0,
                        'status'            =>  'Expired',
                        'p_status'           =>  $aryRes['REFUNDTRANSACTIONID']
                        ), array( 'id'      =>  $txn_id ));

                     wp_safe_redirect(admin_url(). 'admin.php?page=sub_active&refund=yes&status=Success');
                }else {
                    echo "<pre>";
                    print_r($txn_pack);
                    print_r($aryRes);
                    echo "</pre>";
                }                            
                
                
            }
        }
    }
?>

    <div class="wrap"><h2>Refund This Transaction</h2>
        <form method="post" >
        <label> Are you Sure? Want to Refund this transaction</label>

        <p class="submit"><input type="submit" name="submit_form" id="submit" class="button button-primary" value="Refund"></p>
        </form>
    </div> 

<?php 
    /*
     * Partial Refund
     */    


    /*
     *
     * Successful Response
     *
     * REFUNDTRANSACTIONID
     * FEEREFUNDAMT
     * GROSSREFUNDAMT
     * NETREFUNDAMT
     * CURRENCYCODE
     * TIMESTAMP
     * CORRELATIONID
     * ACK
     * VERSION
     * BUILD
     */

    /*
     * Failed Refund Response
     *
     * TIMESTAMP
     * CORRELATIONID
     * ACK
     * VERSION
     * BUILD
     * L_ERRORCODE0
     * L_SHORTMESSAGE0
     * L_LONGMESSAGE0
     * L_SEVERITYCODE0
     */
?>