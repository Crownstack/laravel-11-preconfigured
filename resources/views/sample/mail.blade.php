<!-- Extending the main structure of the mail  -->
@extends('mails.mail')

<!-- To add the header for the email   -->
@section("header")
<header style="text-align: center; padding: 20px 0;">
    <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Welcome to Our Platform</h1>
</header>
@endsection

<!-- To add the body for the email   -->

<!-- To add the footer for the email   -->
@section('footer')
    <footer style="text-align: center; padding: 20px 0; margin-top: 20px; border-top: 1px solid #ddd;">
        <p style="margin-bottom: 10px;">Best regards,<br>The Team</p>
        <p style="margin-bottom: 10px;"><a href="https://www.example.com" style="color: #007bff; text-decoration: none;">www.example.com</a></p>
    </footer>
@endsection