<div class="subscribe-section">
  <div class="container">
    <div class="subscribe-container">
      <div class="decoration"></div>
      
      <span class="offer-badge">
        <i class="bi bi-gift-fill"></i> EXCLUSIVE OFFER
      </span>
      

      <p class="subscribe-text">Join Faysal Traders for exclusive IPS & UPS deals, expert tips, and early access to new arrivals.</p>
      
      <form class="subscribe-form" method="post" action="{{ route('subscribe')}}">
        @csrf
        <input type="email" name="email" class="subscribe-input" placeholder="Enter your email address" required>
        <button type="submit" class="subscribe-btn">Subscribe</button>
      </form>
      
      <div class="privacy-check">
        <input class="form-check-input" type="checkbox" id="privacyCheck" checked>
        <label class="form-check-label" for="privacyCheck">
          I agree to receive emails. <a href="{{ url('/privacy_policy')}}">Privacy Policy</a>
        </label>
      </div>
    </div>
  </div>
</div>