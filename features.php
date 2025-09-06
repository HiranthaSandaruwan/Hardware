<?php include __DIR__ . '/partials/header.php'; ?>
<style>
  .section {
    margin-top: 2rem; 
    margin-bottom: 2rem;
    padding: 2rem;
    border-radius: 12px;
    background-color: #12202e;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease;
  }

  .section:hover {
    transform: translateY(-2px);
  }

  .content h1 {
    color: #ffffff;
    margin-bottom: 1.2rem;
    font-size: 1.8rem;
  }

  .content ul {
    line-height: 1.6;
    color: #ffffff;
    padding-left: 1.5rem;
    margin-bottom: 1rem;
  }

  .content ul ul {
    margin-top: 0.5rem;
    margin-bottom: 1.5rem;
  }

  .content b {
    color: #ade6ff;
    display: inline-block;
    margin-bottom: 0.5rem;
  }

  .content code {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.9em;
  }
</style>
<div class="section">
  <div class="content">
    <h1>Admin Features</h1>
    <ul>
      <li><b>User Management</b></li>
        <ul>
          <li>Approve or reject new customer/technician registrations.</li>
          <li>Disable, or delete customer/technician accounts.</li>
          <li>Manage role-based secure access (Admin, Technician, Customer).</li>
        </ul> 
      <li><b>Repair Workflow Monitoring</b></li>
        <ul>
          <li>Track repair requests from initial submission to final receipt.</li>  
          <li>View system-wide dashboards with pending, ongoing, and completed repairs.</li>
        </ul>      
      <li><b>Financial Oversight</b></li>
        <ul>
          <li>Monitor payment transactions (Online / Cash).</li>
          <li>View pending, completed, and confirmed payments.</li>
        </ul>
    </ul>

  </div>
</div>
<div class="section">
  <div class="content">
    <h1>Technician Features</h1>
    <ul>
      <li><b>Account Access</b>
        <ul>
          <li>Login with default account credentials (username: <code>tech</code>, password: <code>tech</code>) at first use.</li>
        </ul>
      </li>

      <li><b>Task Management</b>
        <ul>
          <li>Access personalized schedules and assigned appointments.</li>
          <li>Receive notifications about new repair requests.</li>
          <li>Update and log repair status in real time (Pending, In Progress, Completed).</li>
        </ul>
      </li>

      <li><b>Customer Interaction</b>
        <ul>
          <li>Provide repair feedback and confirmations after task completion.</li>
        </ul>
      </li>
    </ul>
  </div>
</div>
<div class="section">
  <div class="content">
    <h1>Customer Features</h1>
    <ul>
      <li><b>Account Access</b>
        <ul>
          <li>Login with default account credentials (username: <code>uoc</code>, password: <code>uoc</code>) at first use.</li>
        </ul>
      </li>

      <li><b>Repair Requests</b>
        <ul>
          <li>Submit new repair requests with detailed device information.</li>
          <li>Attach optional notes or issues for better diagnosis.</li>
          <li>Book appointment slots as per convenience.</li>
        </ul>
      </li>

      <li><b>Tracking & Transparency</b>
        <ul>
          <li>Track request progress online (Pending → In Progress → Completed).</li>
          <li>Receive real-time status updates and notifications.</li>
        </ul>
      </li>

      <li><b>Payments</b>
        <ul>
          <li>Choose between Online or Cash payments.</li>
          <li>View payment history and pending bills.</li>
        </ul>
      </li>
    </ul>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>