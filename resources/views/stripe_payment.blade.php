@extends('layouts.master')

@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection

@section('content')
<div class="content-wrapper">
	<br><br>
	<div class="col-md-6">
		<!-- Horizontal Form -->
		<div class="box box-info">
			<form action="/post_stripe_payment" method="post" id="payment-form">
				<div class="form-row">
					<label for="card-element">
					Credit or debit card
					</label>
					<div id="card-element">
					<!-- A Stripe Element will be inserted here. -->
					</div>

					<!-- Used to display Element errors. -->
					<div id="card-errors" role="alert"></div>
				</div>

				<button>Submit Payment</button>
			</form>
		</div>
	</div>
</div>
	<script>
		$(document).ready(function() {
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
})
		var stripe = Stripe('pk_test_1dYa4GnWrcAgZaPnJncYPLYt00jjXSjUNy');
		var elements = stripe.elements();

		// Custom styling can be passed to options when creating an Element.
		var style = {
		base: {
		// Add your base input styles here. For example:
		fontSize: '16px',
		color: "#32325d",
		}
		};

		// Create an instance of the card Element.
		var card = elements.create('card', {style: style});

		// Add an instance of the card Element into the `card-element` <div>.
		card.mount('#card-element');

		card.addEventListener('change', function(event) {
			var displayError = document.getElementById('card-errors');
			if (event.error) {
				displayError.textContent = event.error.message;
			} else {
				displayError.textContent = '';
			}
		});

		var form = document.getElementById('payment-form');
		form.addEventListener('submit', function(event) {
			event.preventDefault();

			stripe.createToken(card).then(function(result) {
				if (result.error) {
					// Inform the customer that there was an error.
					var errorElement = document.getElementById('card-errors');
					errorElement.textContent = result.error.message;
				} else {
					// Send the token to your server.
					stripeTokenHandler(result.token);
				}
			});
		});

		function stripeTokenHandler(token) {
			// Insert the token ID into the form so it gets submitted to the server
			var form = document.getElementById('payment-form');
			var hiddenInput = document.createElement('input');
			hiddenInput.setAttribute('type', 'hidden');
			hiddenInput.setAttribute('name', 'stripeToken');
			hiddenInput.setAttribute('value', token.id);
			form.appendChild(hiddenInput);

			// Submit the form
			form.submit();
		}

	</script>

@endsection


@section('footer')

	@include('layouts.footer')
   
	<!-- <script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script> -->
	<!-- <script src="{{ mix('/js/task.js') }}"></script> -->
   
@endsection
