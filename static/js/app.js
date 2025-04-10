const nav = document.querySelector(".nav");
 
  /* -- (1)  Scroll () -- */
 
window.addEventListener("scroll", fixNav);
function fixNav() {
  if (window.scrollY > nav.offsetHeight + 150) {
    nav.classList.add("active");
  } else {
    nav.classList.remove("active");
  }
}
 
  /* -- (2)  Navigation Bar (NAV bar replaces the header when scrolled up) -- */
 
 
window.addEventListener("scroll", function () {
    let nav = document.querySelector(".nav");
    let header = document.querySelector(".header");
 
    if (window.scrollY > header.offsetHeight) {
      nav.classList.add("fixed");
    } else {
      nav.classList.remove("fixed");
    }
  });
 
// Add smooth scrolling with offset functionality
document.querySelectorAll('.nav-list a').forEach((link) => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    const targetId = link.getAttribute('href').substring(1);
    const targetSection = document.getElementById(targetId);
 
    // Expand the collapsible content first
    const header = targetSection.querySelector('h1');
    if (header) {
      const content = header.parentElement;
      content.classList.add('active'); // Ensure the section is expanded
 
      // Wait for the content to expand before scrolling
      setTimeout(() => {
        const navHeight = document.querySelector('.nav').offsetHeight;
        const targetPosition = targetSection.offsetTop - navHeight;
 
        // Scroll to the adjusted position
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth',
        });
      }, 300); // Delay to allow the content to expand (matches CSS transition time)
    }
  });
});
 
// Toggle collapsible sections on click
document.querySelectorAll('.content h1').forEach((header) => {
  header.addEventListener('click', () => {
    const content = header.parentElement;
    content.classList.toggle('active');
  });
});
 
// Wait 3 seconds before showing the scroll indicator initially
window.addEventListener('load', () => {
  const scrollIndicator = document.querySelector('.scroll-indicator');
  setTimeout(() => {
    scrollIndicator.style.opacity = '1'; // Make it visible
    scrollIndicator.style.pointerEvents = 'auto'; // Enable interaction
  }, 3000); // 3 seconds delay
});
 
// Show or hide the scroll indicator based on scroll position
window.addEventListener('scroll', () => {
  const scrollIndicator = document.querySelector('.scroll-indicator');
  if (window.scrollY === 0) {
    // Show the scroll indicator when at the top
    scrollIndicator.style.opacity = '1';
    scrollIndicator.style.pointerEvents = 'auto';
  } else {
    // Hide the scroll indicator when scrolling down
    scrollIndicator.style.opacity = '0';
    scrollIndicator.style.pointerEvents = 'none';
  }
});
 
// Scroll to the top when the "Sign In" button is clicked
document.querySelector('.sign-in-btn').addEventListener('click', (e) => {
  e.preventDefault(); // Prevent default link behavior
  window.scrollTo({
    top: 0, // Scroll to the top of the page
    behavior: 'smooth', // Smooth scrolling effect
  });
});
 
// Open Privacy Policy Modal
const signInBtn = document.querySelector('.sign-in-btn');
const modal = document.getElementById('privacy-policy-modal');
const closeBtn = document.querySelector('.close-btn');
const agreeCheckbox = document.getElementById('agree-checkbox');
const proceedBtn = document.getElementById('proceed-btn');
const scrollableBoxes = document.querySelectorAll('.scrollable-box');
 
signInBtn.addEventListener('click', (e) => {
  e.preventDefault();
  modal.style.display = 'flex'; // Always show the privacy policy modal
  agreeCheckbox.disabled = true; // Disable the checkbox initially
});
 
// Close Privacy Policy Modal
closeBtn.addEventListener('click', () => {
  modal.style.display = 'none'; // Hide the modal
});
 
// Enable Proceed Button when Checkbox is Checked
agreeCheckbox.addEventListener('change', () => {
  proceedBtn.disabled = !agreeCheckbox.checked; // Enable/disable button
});
 
// Proceed to Sign In
proceedBtn.addEventListener('click', () => {
  modal.style.display = 'none'; // Hide the privacy policy modal
  openSignInModal(); // Open the sign-in modal
});
 
const heroContent = document.querySelector('.hero-content');
 
// Open Sign-In Modal
function openSignInModal() {
  const signInModal = document.getElementById('sign-in-modal');
  const modalContent = signInModal.querySelector('.modal-content');
  const heroContent = document.querySelector('.hero-content'); // Select the hero content
 
  // Restore the original Sign-In modal content
  modalContent.innerHTML = `
    <span class="sign-in-close-btn">&times;</span>
    <h1 class="modal-title">Sign In</h1>
    <form id="sign-in-form">
      <div class="input-group">
        <label for="email">Email</label>
        <div class="input-wrapper">
          <input type="email" id="email" placeholder="Enter your email" required />
          <img src="/static/images/components/email_logo.png" alt="Email Icon" class="input-icon" />
        </div>
        <span class="error email-error"></span>
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <div class="input-wrapper">
          <input type="password" id="password" placeholder="Enter your password" required />
          <img src="/static/images/components/login_password.png" alt="Toggle Password" class="input-icon toggle-password" />
        </div>
        <span class="error password-error"></span>
      </div>
      <button type="submit" class="btn btn-primary">Sign In</button>
    </form>
    <p class="subtitle">
      By creating an account, you agree with our
      <a href="#privacy-policy">Privacy Policy</a> and <a href="#terms-of-use">Terms of Use</a>.
    </p>
    <div class="links">
      <a href="#" id="receive-otp">Receive an OTP (One-time Password)</a>
      <a href="#" id="forgot-password">Forgot Password?</a>
    </div>
  `;
 
  // Show the modal
  signInModal.style.display = 'flex';
 
  // Add the shift-right class to the hero content for the animation
  heroContent.classList.add('shift-right');
 
  // Close Sign-In Modal
  modalContent.querySelector('.sign-in-close-btn').addEventListener('click', () => {
    signInModal.style.display = 'none';
    resetHeroContentPosition(); // Reset the hero content position
  });
 
  // Reattach event listeners for OTP and Forgot Password
  document.getElementById('receive-otp').addEventListener('click', (e) => {
    e.preventDefault();
    openOtpModal();
  });
 
  document.getElementById('forgot-password').addEventListener('click', (e) => {
    e.preventDefault();
    openForgotPasswordModal();
  });
 
  // Reattach validation for Sign-In Form
  document.getElementById('sign-in-form').addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the default form submission behavior
 
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
 
    let isValid = true;
 
    // Email validation for specific domains
    const validDomains = ['@student.apc.edu.ph', '@apc.edu.ph'];
    const isValidEmail = validDomains.some((domain) => email.endsWith(domain));
 
    if (!isValidEmail) {
      document.querySelector('.email-error').textContent = 'Invalid email address. Please use @student.apc.edu.ph or @apc.edu.ph.';
      isValid = false;
    } else {
      document.querySelector('.email-error').textContent = '';
    }
 
    // Password validation (minimum 12 characters)
    if (password.length < 12) {
      document.querySelector('.password-error').textContent = 'Password must be at least 12 characters.';
      isValid = false;
    } else {
      document.querySelector('.password-error').textContent = '';
    }
 
    if (isValid) {
      alert('Sign-In Successful! Redirecting to the home page...');
      window.location.href = 'home.html';
    }
  });
 
  // Reattach toggle password visibility
  modalContent.querySelector('.toggle-password').addEventListener('click', (e) => {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    e.target.classList.toggle('show'); // Toggle the eye icon
  });
}
 
// Close Sign-In Modal
document.querySelector('.sign-in-close-btn').addEventListener('click', () => {
  const signInModal = document.getElementById('sign-in-modal');
  signInModal.style.display = 'none'; // Hide the sign-in modal
  heroContent.classList.remove('shift-right'); // Remove class to reset position
});
 
// Toggle Password Visibility
document.querySelector('.toggle-password').addEventListener('click', (e) => {
  const passwordInput = document.getElementById('password');
  const type = passwordInput.type === 'password' ? 'text' : 'password';
  passwordInput.type = type;
  e.target.classList.toggle('show'); // Toggle the eye icon
});
 
// Validate Sign-In Form
document.getElementById('sign-in-form').addEventListener('submit', (e) => {
  e.preventDefault(); // Prevent the default form submission behavior
  console.log('Sign-In Form Submitted'); // Debugging
 
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
 
  console.log('Email:', email); // Debugging
  console.log('Password:', password); // Debugging
 
  let isValid = true;
 
  // Email validation for specific domains
  const validDomains = ['@student.apc.edu.ph', '@apc.edu.ph'];
  const isValidEmail = validDomains.some((domain) => email.endsWith(domain));
 
  if (!isValidEmail) {
    document.querySelector('.email-error').textContent = 'Invalid email address. Please use @student.apc.edu.ph or @apc.edu.ph.';
    isValid = false;
  } else {
    document.querySelector('.email-error').textContent = '';
  }
 
  // Password validation (minimum 12 characters)
  if (password.length < 12) {
    document.querySelector('.password-error').textContent = 'Password must be at least 12 characters.';
    isValid = false;
  } else {
    document.querySelector('.password-error').textContent = '';
  }
 
  if (isValid) {
    console.log('Validation Passed'); // Debugging
    alert('Sign-In Successful! Redirecting to the home page...');
    window.location.href = 'home.html';
  }
});
 
// Close Modal when Clicking Outside
window.addEventListener('click', (e) => {
  if (e.target === modal) {
    modal.style.display = 'none';
  }
});
 
// Enable Checkbox Only After Scrolling Through All Content
scrollableBoxes.forEach((box) => {
  box.addEventListener('scroll', () => {
    const isAtBottom = box.scrollTop + box.clientHeight >= box.scrollHeight;
    if (isAtBottom) {
      // Check if all scrollable boxes have been scrolled to the bottom
      const allScrolled = Array.from(scrollableBoxes).every((b) =>
        b.scrollTop + b.clientHeight >= b.scrollHeight
      );
      if (allScrolled) {
        agreeCheckbox.disabled = false; // Enable the checkbox
      }
    }
  });
});
 
// Open OTP Modal (Reusing the Sign-In Modal)
document.getElementById('receive-otp').addEventListener('click', (e) => {
  e.preventDefault();
  openOtpModal(); // Open the OTP modal
});
 
let failedOtpAttempts = 0; // Track failed OTP attempts
let otpCooldown = false; // Track if the user is in cooldown
 
// Open OTP Modal Function
function openOtpModal() {
  const signInModal = document.getElementById('sign-in-modal');
  const modalContent = signInModal.querySelector('.modal-content');
 
  // Update modal content for OTP
  modalContent.innerHTML = `
    <span class="sign-in-close-btn">&times;</span>
    <h1 class="modal-title">Receive an OTP<br>(One-time Password)</h1>
    <p class="subtitle">If the email is registered, you will receive an OTP.</p>
    <form id="otp-form">
      <div class="input-group">
        <label for="otp-email">Email</label>
        <div class="input-wrapper">
          <input type="email" id="otp-email" placeholder="Enter your email" required />
          <img src="/static/images/components/email_logo.png" alt="Email Icon" class="input-icon" />
        </div>
        <span class="error otp-email-error"></span>
      </div>
      <button type="submit" class="btn btn-primary" id="send-otp-btn">SEND OTP</button>
      <a href="#" id="resend-otp-link" style="display: none; color: blue; text-decoration: underline;">Resend OTP</a>
      <span class="error otp-failed-error" style="display: none; color: red; font-size: 12px;"></span>
    </form>
    <p class="subtitle">
      By creating an account, you agree with our
      <a href="#privacy-policy">Privacy Policy</a> and <a href="#terms-of-use">Terms of Use</a>.
      <br>
      <a href="#" id="back-to-sign-in">Back to Sign In</a>
    </p>
  `;
 
  // Show the modal
  signInModal.style.display = 'flex';
 
  // Close OTP Modal
  modalContent.querySelector('.sign-in-close-btn').addEventListener('click', () => {
    signInModal.style.display = 'none';
    resetHeroContentPosition(); // Reset the hero content position
  });
 
  // Back to Sign In
  modalContent.querySelector('#back-to-sign-in').addEventListener('click', (e) => {
    e.preventDefault();
    openSignInModal(); // Reopen the Sign-In modal
  });
 
  // Handle OTP Form Submission
  modalContent.querySelector('#otp-form').addEventListener('submit', (e) => {
    e.preventDefault();
 
    if (otpCooldown) {
      return; // Prevent further attempts during cooldown
    }
 
    const email = document.getElementById('otp-email').value.trim();
    const validDomains = ['@student.apc.edu.ph', '@apc.edu.ph'];
    const isValidEmail = validDomains.some((domain) => email.endsWith(domain));
 
    if (!isValidEmail) {
      modalContent.querySelector('.otp-email-error').textContent = 'Invalid email address. Please use (Eg. @student.apc.edu.ph and @apc.edu.ph).';
    } else {
      modalContent.querySelector('.otp-email-error').textContent = '';
      sendOtp(email); // Simulate sending OTP
    }
  });
 
  // Handle Resend OTP
  modalContent.querySelector('#resend-otp-link').addEventListener('click', (e) => {
    e.preventDefault();
 
    if (otpCooldown) {
      return; // Prevent resending during cooldown
    }
 
    const email = document.getElementById('otp-email').value.trim();
    sendOtp(email); // Simulate resending OTP
  });
  
 
  function sendOtp(email) {
    // Simulate OTP sending logic
    console.log(`OTP sent to ${email}`);
    alert('OTP has been sent to your email.');
 
    // Replace the button with the "Resend OTP" link
    document.getElementById('send-otp-btn').style.display = 'none';
    document.getElementById('resend-otp-link').style.display = 'inline';
 
    // Simulate OTP verification failure for demonstration
    failedOtpAttempts++;
    if (failedOtpAttempts >= 3) {
      startCooldown();
    }
  }
 

  
  function startCooldown() {
    otpCooldown = true;
    const errorElement = modalContent.querySelector('.otp-failed-error');
    errorElement.textContent = 'The system has detected too many failed sign-in attempts. Please wait 5 minutes before trying again.';
    errorElement.style.display = 'block';
 
    // Disable the "Resend OTP" link
    const resendLink = document.getElementById('resend-otp-link');
    resendLink.style.pointerEvents = 'none';
    resendLink.style.color = 'gray';
 
    // Start a 5-minute cooldown
    setTimeout(() => {
      otpCooldown = false;
      failedOtpAttempts = 0; // Reset failed attempts
      errorElement.style.display = 'none';
      resendLink.style.pointerEvents = 'auto';
      resendLink.style.color = 'blue';
    }, 5 * 60 * 1000); // 5 minutes
  }
}
 
// Open Forgot Password Modal (Reusing the Sign-In Modal)
document.getElementById('forgot-password').addEventListener('click', (e) => {
  e.preventDefault();
  openForgotPasswordModal(); // Open the Forgot Password modal
});
 
// Open Forgot Password Modal Function
function openForgotPasswordModal() {
  const signInModal = document.getElementById('sign-in-modal');
  const modalContent = signInModal.querySelector('.modal-content');
 
  // Update modal content for Forgot Password
  modalContent.innerHTML = `
    <span class="sign-in-close-btn">&times;</span>
    <h1 class="modal-title">Forgot Password</h1>
    <p class="subtitle">Enter your registered email to reset your password.</p>
    <form id="forgot-password-form">
      <div class="input-group">
        <label for="forgot-email">Email</label>
        <div class="input-wrapper">
          <input type="email" id="forgot-email" placeholder="Enter your email" required />
          <img src="/static/images/components/email_logo.png" alt="Email Icon" class="input-icon" />
        </div>
        <span class="error forgot-email-error"></span>
      </div>
      <button type="submit" class="btn btn-primary">RESET PASSWORD</button>
    </form>
    <p class="subtitle">
      By creating an account, you agree with our
      <a href="#privacy-policy">Privacy Policy</a> and <a href="#terms-of-use">Terms of Use</a>.
      <br>
      <a href="#" id="back-to-sign-in">Back to Sign In</a>
    </p>
  `;
 
  // Show the modal
  signInModal.style.display = 'flex';
 
  // Close Forgot Password Modal
  modalContent.querySelector('.sign-in-close-btn').addEventListener('click', () => {
    signInModal.style.display = 'none';
    resetHeroContentPosition(); // Reset the hero content position
  });
 
  // Back to Sign In
  modalContent.querySelector('#back-to-sign-in').addEventListener('click', (e) => {
    e.preventDefault();
    openSignInModal(); // Reopen the Sign-In modal
  });
 
  // Validate Forgot Password Form
  modalContent.querySelector('#forgot-password-form').addEventListener('submit', (e) => {
    e.preventDefault();
 
    const email = document.getElementById('forgot-email').value.trim();
    const validDomains = ['@student.apc.edu.ph', '@apc.edu.ph'];
    const isValidEmail = validDomains.some((domain) => email.endsWith(domain));
 
    if (!isValidEmail) {
      modalContent.querySelector('.forgot-email-error').textContent = 'Invalid email address. Please use (Eg. @student.apc.edu.ph and @apc.edu.ph).';
    } else {
      modalContent.querySelector('.forgot-email-error').textContent = '';
      alert('If the email is registered, you will receive a password reset link.');
    }
  });
}
 
// Reset Hero Content Position
function resetHeroContentPosition() {
  const heroContent = document.querySelector('.hero-content');
  heroContent.classList.remove('shift-right'); // Remove the shift-right class to reset the position
}
