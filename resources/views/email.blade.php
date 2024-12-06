<!-- resources/views/emails/send.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ $emailDto->subject }}</title>
</head>
<body>
<h1>{{ $emailDto->subject }}</h1>
<p>Id: {{ $emailDto->body['id'] }}</p>
<p>User Id: {{ $emailDto->body['user_id'] }}</p>
<p>Book Id: {{ $emailDto->body['book_id'] }}</p>
<p>Loan date: {{ $emailDto->body['loan_date'] }}</p>
<p>Return date: {{ $emailDto->body['return_date'] }}</p>
</body>
</html>


