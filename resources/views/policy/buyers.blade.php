@extends('layout')

@section('title', 'Color Creep')

@section('sidebar')
    @parent
@stop

<style>
    #content {
        height: 40rem;
        overflow: scroll;
    }
</style>
@section('content')
    GENERAL
    <br><br>
    Losses in shipping
    Some of our sellers offer insurance. If insurance is offered for a product, a shipping loss will be the liability of the buyer. If insurance is not offered, a shipping loss will be the liability of the seller. There may be exceptions and those cases will be arbitrated by this marketplace.
    <br><br>
    BUYERS
    <br><br>
    When you submit feedback, you can select whether there was a problem or not with your order. This will alert our customer service team to the problem.
    <br><br>
    If no ticket has been opened within 30 days of the transaction date, we will not be able to assist in resolution of the problem. The buyer is still encouraged to use our webmail system to communicate directly with their seller.
    <br><br>
    <!-- Seller fails to confirm orders
    If the seller doesn't confirm an order you've placed in 3 business days, you may cancel the order through a request ticket. -->
    <br><br>
    Transaction feedback
    We reserve the right to delete ratings and feedback if deemed inappropriate or offensive such as threats or profanity.
    <br><br>
    Shipping address
    Orders are shipped to your PayPal address, which must match your Color Creep registered address. If your address here wasn't current you may be asked to pay for postage to re-ship the order once it bounces back to the seller.
    <br><br>
    SELLERS
    <br><br>
    *Verified PayPal account required
    You must have a verified PayPal account set up before you can list cards to sell on Color Creep. This was part of the member agreement you consented to when you signed up. If your PayPal account is frozen, you can request us to redirect your payments to another email.
    <br><br>
    *Customer service tickets
    ColorCreep employs a customer service team backed by a ticketing system. Tickets may be opened by the buyer on orders that are late, damaged, or have another problem from 6 - 30 days after the transaction date. You, the seller, have 5 business days to respond to this ticket. Failure to respond may result in a refund to your buyer at your expense.
    <br><br>
    Payment schedules
    Your personal payment schedule depends on your seller level. This schedule is used to help prevent fraud from unproven sellers.
    <br><br>
    MEMBERSHIP
    <br><br>
    Bounced email
    If email to your account bounces for any reason, we reserve the rightsuspend it until a communication channel can be re-established.
    <br><br>
    You are allowed one account per person.
    <br><br>
    Webmail system
    ColorCreep provides a messaging system for users to send notes and questions to one another about items for sale, purchases made, orders received, and other topics pertaining to Color Creep related business only. This system is not for use in trying to buy or trade cards outside of Color Creep or promote your own business. Abuse of the system may result in your account being closed.
    <br><br>
    Unwanted US Mail
    ColorCreep is intended as a forum for members to buy and sell cards from each other. Using member's mailing addresses to send Postal mail to advertise, solicit, send chain letters, or otherwise send unwanted mail to members is not allowed and grounds for suspension. No additional materials that do not relate to the transaction at hand can be included with an order.

@stop

@section('footer')
    @parent
@stop
