<?php include __DIR__ . '/partials/header.php'; ?>
<style>
  body {
      background-color: #f7f5f5ff;
      color: #ffffff;
      font-family: Arial, sans-serif;
      margin: 20px; 
    }
  .section{
    margin-top: 20px;
    padding: 15px 10px 20px 20px;
    border: 1px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    background-color: #12202e;
  }
  .section:hover{
    transform:scale(1.01);
  }
  .content ul{   
    line-height: 1.6;
    color: #ffffff;
    padding-left: 25px;
  }
  .content h1{
    color: #fffffeff;
    padding-bottom:7px;
  }
</style>
<div class="section">
  <div class="content">
    <h1>Admin Features</h1>
    <ul>
        <li>Approve or reject new customer registrations.</li>
        <li>Create and manage technician accounts.</li>
        <li>Monitor all repair workflows from request to receipt.</li>
        <li>Role-based secure access control.</li>
    </ul>

  </div>
</div>
<div class="section">
  <div class="content">
    <h1>Technician Features</h1>
    <ul>
        <li>Use default account tech / tech for first login.</li>
        <li>Access personalized schedules and appointments.</li>
        <li>Update repair status in real time.</li>
        <li>Ensure receipts are auto-generated upon repair completion.</li>
    </ul>
  </div>
</div>
<div class="section">
  <div class="content">
    <h1>Customer Features</h1>
    <ul>
        <li>Use default account uoc / uoc for first login</li>
        <li>Submit repair requests with device details.</li>
        <li>Book appointment slots for drop-off.</li>
        <li>Track request progress online.</li>
        <li>Download or print receipts after service.</li>
    </ul>

  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>