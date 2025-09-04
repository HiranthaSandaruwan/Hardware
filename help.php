<?php include __DIR__ . '/partials/header.php'; ?>
  <style>
    body {
      background-color: #f7f5f5ff;
      color: #ffffff;
      font-family: Arial, sans-serif;
      margin: 20px; 
    }
    h1 {
      color: #fffffeff;
    }
    p, li {
      line-height: 1.6;
    }
    ol {
      margin-left: 20px;
    }
    .help {
      margin-top: 20px;
      padding: 15px 10px 20px 20px;
      border: 1px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      background-color: #121619a3;
      /* background:linear-gradient(to right, #12202e, white); */
    }
    .help:hover{
      transform:scale(1.01);
    }
    .contact-section {
      /* margin-top: 40px; */
      padding: 20px;
      border: 1px solid #333;
      border-radius: 5px;
      background-color: 121619a3
      cursor: pointer;
    }
  </style>
  <div class="help">
    <div style="padding-left:10px;">
      <h1>Hardware Repair Service Help Center</h1>
      <p>Welcome to our Hardware Repair Service platform. This comprehensive guide will help you navigate through the system based on your role.</p>
      <div style="margin: 20px 0; padding: 15px; border-radius: 5px;">
        <h3 style="color: #ade6ffff;">Getting Started</h3>
        <ol>
          <li>Register an account as either a Customer or Technician</li>
          <li>Wait for Admin approval of your account</li>
          <li>Once approved, log in and start using the platform based on your role</li>
        </ol>
      </div>
  </div>
  </div>

  <div class = "help"><!--Admin help-->
    <div style="padding-left:10px;">
      <h2 style="color: #ffffffff;">Administrator Guide</h2>
      <div style="margin: 10px 0;">
        <h4 style="color: #ade6ffff;">User Management</h4>
        <ul>
          <li>Review and approve new user registrations in 'Users Pending'</li>
          <li>Manage existing users in 'Users Manage'</li>
          <li>Monitor technician qualifications and credentials</li>
        </ul>
        
        <h4 style="color:#ade6ffff;">Request Oversight</h4>
        <ul>
          <li>View all repair requests in 'Requests'</li>
          <li>Monitor pending requests in 'Requests Pending'</li>
          <li>Track repair status and progress</li>
        </ul>
        
        <h4 style="color:#ade6ffff;">System Monitoring</h4>
        <ul>
          <li>Generate and analyze reports on system performance</li>
          <li>Review customer and technician feedback</li>
          <li>Monitor payment transactions and financial records</li>
        </ul>
      </div>
  </div>
  </div>

  <div class = "help"><!--Technician Help-->
    <div style="padding-left:10px;">
      <h2 style="color: #ffffffff;">Technician Guide</h2>
      <div style="margin: 10px 0;">
        <h4 style="color: #ade6ffff;">Managing Repair Requests</h4>
        <ul>
          <li>View available repair requests in 'Requests'</li>
          <li>Send proposals to customers for repair work</li>
          <li>Track your accepted appointments</li>
          <li>Update repair status as you progress</li>
        </ul>
        
        <h4 style="color: #ade6ffff;">Completing Repairs</h4>
        <ul>
          <li>Document repair details and parts used</li>
          <li>Create detailed receipts for completed work</li>
          <li>Update repair status to 'Completed' when finished</li>
          <li>Request customer feedback for completed repairs</li>
        </ul>
      </div>
  </div>
  </div>

  <div class = "help"><!--user help-->
    <div style="padding-left:10px;">
      <h2 style="color: #fff;">Customer Guide</h2>
      <div style="margin: 10px 0;">
        <h4 style="color: #ade6ffff;">Submitting Repair Requests</h4>
        <ul>
          <li>Create new repair requests with detailed descriptions</li>
          <li>Upload photos of damaged hardware if needed</li>
          <li>Review repair proposals from technicians</li>
          <li>Accept preferred repair proposals</li>
        </ul>
        
        <h4 style="color: #ade6ffff;">Managing Your Repairs</h4>
        <ul>
          <li>Track the status of your repair requests</li>
          <li>View and pay repair invoices</li>
          <li>Communicate with assigned technicians</li>
          <li>Provide feedback after repair completion</li>
        </ul>
      </div>
  </div>
  </div>

<div id="contact" class="contact-section help">
  <div style="padding-left:10px;">
      <div class="container center">
        <div class="section-header">
          <h2 class="section-title">Let's Connect</h2>
          <p class="section-subtitle">
            You have some issue?tell it!
          </p>
        </div>
        <div class="contact-content">
          <div class="contact-info">
            <div
              class="contact-item"
              onclick="window.open('mailto:hardwaretraker@gmail.com')"
            >
              <div class="contact-icon">
                <svg
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                >
                  <path
                    d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"
                  />
                </svg>
              </div>
              <div style="font-weight: 600; color: #ffffff; margin-top: 10px">
                Email
              </div>
              <div style="color: #cccccc; font-size: 0.9em">Click to mail</div>
  </div>
            </div>

<?php include __DIR__ . '/partials/footer.php'; ?>