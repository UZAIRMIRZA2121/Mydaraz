<form method="POST" action="{{ route('payment.showIframe') }}">
    @csrf
    <label>Select Payment Method:</label><br>
    <select name="payment_method" required>
        <option value="all">All Methods</option>
        <option value="card">Credit/Debit Card</option>
        <option value="mada">MADA</option>
        <option value="applepay">Apple Pay</option>
        <option value="stcpay">STCPay</option>
    </select>

    <button type="submit">Proceed to Pay</button>
</form>
