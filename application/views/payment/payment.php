

<script> // after transaction
  paypal.Buttons({
    createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '16.00'
          },
          description: "fake item purchase",
          custom_id: '12333',
        }]
      });
    },
    onApprove: function(data, actions) {
      // This function captures the funds from the transaction.
      return actions.order.capture().then(function(details) {
        // This function shows a transaction success message to your buyer.
        alert('Transaction completed by ' + details.payer.name.given_name);
      });
    }
  }).render('#paypal-button-container');
  //This function displays Smart Payment Buttons on your web page.
</script>

<div class="container">
    <div class="row">
        <div class="col"></div>
        <div id="paypal-button-container" class="col">
          
        </div>

        <div class="col"></div>
    </div>
</div>