Run
Copy code
<!DOCTYPE html>
<html>
<head>
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
}

.container {
  width: 400px;
  margin: 50px auto;
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.logo {
  text-align: center;
  margin-bottom: 20px;
}

.logo img {
  width: 50px;
}

.invoice-title {
  text-align: center;
  font-size: 1.5em;
  font-weight: bold;
  margin-bottom: 10px;
}

.invoice-info {
  text-align: center;
  margin-bottom: 20px;
}

.invoice-amount {
  background-color: #f2f2f2;
  padding: 20px;
  border-radius: 5px;
  margin-bottom: 20px;
}

.invoice-amount h2 {
  font-size: 2em;
  font-weight: bold;
  margin-bottom: 10px;
}

.invoice-amount p {
  font-size: 1.2em;
  font-weight: bold;
}

.invoice-button {
  display: block;
  width: 150px;
  margin: 0 auto;
  padding: 10px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.invoice-message {
  margin-top: 20px;
}
</style>
</head>
<body>

<div class="container">
  <div class="logo">
    <img src="https://i.imgur.com/W0QvZvW.png" alt="Logo">
  </div>

  <div class="invoice-title">
    New Fees
  </div>

  <div class="invoice-info">
    Invoice
  </div>

  <div class="invoice-amount">
    <h2>{{$fee}}</h2>
    <p>{{$date}}</p>
    <button class="invoice-button">Pay Fees</button>
  </div>

  <div class="invoice-message">
    <p>Dear {{$student_name}},</p>
    <p>Here is your Fees. We will process youy walllet during a month.</p>
    <p>Thanks you for your Cooperation!</p>

  </div>

</div>

</body>
