document.addEventListener('DOMContentLoaded', () => {
  const nav = document.querySelector(".nav");

  /* -- (1)  Scroll () -- */

  // window.addEventListener("scroll", fixNav);
  // function fixNav() {
  //   if (window.scrollY > nav.offsetHeight + 150) {
  //     nav.classList.add("active");
  //   } else {
  //     nav.classList.remove("active");
  //   }
  // }

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
      const href = link.getAttribute('href');
      if (!href || href === '#' || !href.startsWith('#')) return; // Skip invalid links

      const targetId = href.substring(1);
      const targetSection = document.getElementById(targetId);

      if (!targetSection) return; // Skip if target section does not exist

      e.preventDefault();

      // Expand the collapsible content first
      const header = targetSection.querySelector('h1');
      if (header) {
        const content = header.parentElement;
        if (content) content.classList.add('active');

        setTimeout(() => {
          const navHeight = document.querySelector('.nav').offsetHeight;
          const targetPosition = targetSection.offsetTop - navHeight;

          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth',
          });
        }, 300);
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
  const signInBtn = document.querySelector('.sign-in-btn');
  if (signInBtn) {
    signInBtn.addEventListener('click', (e) => {
      e.preventDefault(); // Prevent default link behavior
      smoothScrollToTopAndOpenModal();
    });
  }

  // Open Privacy Policy Modal
  const modal = document.getElementById('privacy-policy-modal');
  const closeBtn = document.querySelector('.close-btn');
  const agreeCheckbox = document.getElementById('agree-checkbox');
  const proceedBtn = document.getElementById('proceed-btn');
  const scrollableBoxes = document.querySelectorAll('.scrollable-box');

  // Helper to detect if we're already at the top
  function isAtTop() {
    return window.scrollY === 0;
  }

  // Smooth scroll to top and then open modal
  function smoothScrollToTopAndOpenModal() {
    if (isAtTop()) {
      openPrivacyPolicyModal();
    } else {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      // Listen for scroll end
      let checkInterval = setInterval(() => {
        if (isAtTop()) {
          clearInterval(checkInterval);
          openPrivacyPolicyModal();
        }
      }, 10);
    }
  }

  // Refactor: openPrivacyPolicyModal logic
  function openPrivacyPolicyModal() {
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');
    agreeCheckbox.disabled = true;
  }

  // Close Privacy Policy Modal
  closeBtn.addEventListener('click', () => {
    modal.style.display = 'none'; // Hide the modal
    document.body.classList.remove('modal-open'); // Remove class when closing modal
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

  // Function to check viewport width
  function isMobileView() {
    return window.innerWidth <= 768;
  }

  // Update modal position based on viewport
  function updateModalPosition() {
    const signInModal = document.getElementById('sign-in-modal');
    const modalContent = signInModal.querySelector('.modal-content');
    const heroContent = document.querySelector('.hero-content');

    if (isMobileView()) {
      modalContent.style.left = '50%';
      modalContent.style.transform = 'translate(-50%, -50%)';
      heroContent.classList.remove('shift-right');
    } else {
      modalContent.style.left = '20px';
      modalContent.style.transform = 'translateY(-50%)';
      if (signInModal.style.display === 'flex') {
        heroContent.classList.add('shift-right');
      }
    }
  }

  // Open Sign-In Modal
  function openSignInModal() {
    const signInModal = document.getElementById('sign-in-modal');
    document.body.classList.add('modal-open'); // Add class when opening modal
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
            <img src="/rias/static/images/components/email_logo.png" alt="Email Icon" class="input-icon email-icon" />
            <span class="email-tooltip">Allowed: @student.apc.edu.ph, @apc.edu.ph</span>
          </div>
          <span class="error email-error"></span>
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <input type="password" id="password" placeholder="Enter your password" required />
            <img src="../../../static/images/components/login_password.png" alt="Toggle Password" class="input-icon toggle-password" />
          </div>
          <span class="error password-error"></span>
        </div>
        <button type="submit" class="btn btn-primary">Sign In</button>
      </form>
      <p class="subtitle">
        By signing in, you agree to our
        <a href="#privacy-policy">Privacy Policy</a> and <a href="#terms-of-use">Terms of Use</a>.
      </p>
      <div class="links">
        <a href="#" id="receive-otp">Receive an OTP (One-time Password)</a>
        <a href="#" id="forgot-password">Forgot Password?</a>
      </div>
    `;

    // Show the modal with fade-in effect
    signInModal.style.opacity = '0';
    signInModal.style.display = 'flex';
    updateModalPosition();

    // Trigger reflow for animation
    void signInModal.offsetWidth;
    signInModal.style.opacity = '1';

    // Add listeners
    attachModalListeners(modalContent);

    // Reset to hidden state every time modal opens
    const passwordInput = document.getElementById('password');
    const passwordIcon = modalContent.querySelector('.toggle-password');
    if (passwordInput && passwordIcon) {
      passwordInput.type = 'password';
      passwordIcon.src = '../../../static/images/components/login_password.png';
    }
  }

  function attachModalListeners(modalContent) {
    // Close button handler
    modalContent.querySelector('.sign-in-close-btn').addEventListener('click', () => {
      const signInModal = document.getElementById('sign-in-modal');
      closeModalWithAnimation(signInModal);
    });

    // Reattach other event listeners
    document.getElementById('receive-otp').addEventListener('click', (e) => {
      e.preventDefault();
      openOtpModal();
    });

    document.getElementById('forgot-password').addEventListener('click', (e) => {
      e.preventDefault();
      openForgotPasswordModal();
    });

    modalContent.querySelector('#sign-in-form').addEventListener('submit', async (e) => {
      e.preventDefault();

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
      if (password.length < 0) {
        document.querySelector('.password-error').textContent = 'Password must be at least 12 characters.';
        isValid = false;
      } else {
        document.querySelector('.password-error').textContent = '';
      }

      if (!isValid) return;

      // Show loading state
      const submitButton = document.querySelector('#sign-in-form button[type="submit"]');
      const originalButtonText = submitButton.textContent;
      submitButton.disabled = true;
      submitButton.textContent = 'Signing in...';

      try {
        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);
        formData.append('otp', 0);

        const response = await fetch('signin.php', {
          method: 'POST',
          body: formData
        });
      
        const raw = await response.text(); // get raw response first
        console.log('RAW RESPONSE:', raw);
      
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
      
        let result;
        try {
          result = JSON.parse(raw); // manually parse it
        } catch (err) {
          throw new Error("BROKEN JSON: " + err.message);
        }
      
        console.log('Parsed JSON:', result);
        
        console.log('Login response:', result.status);
        if (result.status === 'success') {
          const userRole = result.role.toLowerCase().trim();
          console.log('User role:', userRole);

          let redirectPath;
          switch (userRole) {
            case 'admin':
              redirectPath = '/rias/templates/schooluser/admin/_index.php';
              break;
            case 'executive director':
              redirectPath = '/rias/templates/schooluser/faculty/_index.php';
              break;
            case 'program director':
              redirectPath = '/rias/templates/schooluser/faculty/_index.php';
              break;
            case 'internship officer':
              redirectPath = '/rias/templates/schooluser/faculty/_index.php';
              break;
            case 'student intern':
              redirectPath = '/rias/templates/schooluser/student/_index.php';
              break;
            default:
              console.error('Unknown role:', userRole);
              document.querySelector('.email-error').textContent = 'Invalid user role. Please contact support.';
              submitButton.disabled = false;
              submitButton.textContent = originalButtonText;
              return;
          }
          sessionStorage.removeItem('resendOtp');
          sessionStorage.removeItem('otpEmailCooldownEnd');
          sessionStorage.removeItem('otpCooldownEnd');
          sessionStorage.removeItem('lastOtpEmail');

          console.log('Redirecting to:', redirectPath);
          window.location.replace(redirectPath);
        } else {
          // Show error message
          if (result.message.includes('Invalid password')) {
            document.querySelector('.password-error').textContent = 'Incorrect password. Please try again.';
          } else if (result.message.includes('User not found')) {
            document.querySelector('.email-error').textContent = 'No active account found with this email.';
          } else {
            document.querySelector('.email-error').textContent = result.message || 'An error occurred. Please try again.';
          }
          submitButton.disabled = false;
          submitButton.textContent = originalButtonText;
        }
      } catch (error) {
        console.error('Login error:', error);
        document.querySelector('.email-error').textContent = 'Connection error. Please try again later.';
        submitButton.disabled = false;
        submitButton.textContent = originalButtonText;
      }
    });

    modalContent.querySelector('.toggle-password').addEventListener('click', (e) => {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;
      // Toggle icon based on visibility
      if (type === 'text') {
        e.target.src = '/rias/static/images/components/login_password_show.png'; // Eye open
      } else {
        e.target.src = '/rias/static/images/components/login_password.png'; // Eye closed
      }
    });

    // Scroll to Privacy Policy and Terms of Use from modal
    modalContent.querySelectorAll('a[href="#privacy-policy"], a[href="#terms-of-use"]').forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = link.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);

        if (targetSection) {
          closeModalWithAnimation(document.getElementById('sign-in-modal'));
          setTimeout(() => {
            // Remove 'active' from all, then add to target (for collapsible)
            document.querySelectorAll('.content').forEach(sec => sec.classList.remove('active'));
            targetSection.classList.add('active');

            // Wait for the content to expand before scrolling (matches nav logic)
            setTimeout(() => {
              const navHeight = document.querySelector('.nav').offsetHeight;
              const targetPosition = targetSection.offsetTop - navHeight;
              window.scrollTo({
                top: targetPosition,
                behavior: 'smooth',
              });
            }, 300); // Delay to allow the content to expand (matches CSS transition time)
          }, 350); // Wait for modal close animation
        }
      });
    });
  }

  function closeModalWithAnimation(modal) {
    modal.style.opacity = '0';
    resetHeroContentPosition();
    document.body.classList.remove('modal-open'); // Remove class when closing modal
    setTimeout(() => {
      modal.style.display = 'none';
      modal.style.opacity = '1';
    }, 300);
  }

  // Update position on resize
  window.addEventListener('resize', updateModalPosition);

  // Close Sign-In Modal
  document.querySelector('.sign-in-close-btn').addEventListener('click', () => {
    const signInModal = document.getElementById('sign-in-modal');
    closeModalWithAnimation(signInModal);
  });

  // Toggle Password Visibility
  document.querySelector('.toggle-password').addEventListener('click', (e) => {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    e.target.classList.toggle('show'); // Toggle the eye icon
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

  let failedOtpAttempts = 3; // Track failed OTP attempts
  let otpCooldown = false; // Track if the user is in cooldown
  let forgotPasswordCooldown = false; // Track if the user is in cooldown

  // Open OTP Modal Function

  function openOtpModal() {
    const signInModal = document.getElementById('sign-in-modal');
    const modalContent = signInModal.querySelector('.modal-content');
    const savedEmailCooldownEnd = parseInt(sessionStorage.getItem('otpEmailCooldownEnd'));
    const now = Math.floor(Date.now() / 1000);
    const email = sessionStorage.getItem('lastOtpEmail');
  
    signInModal.style.display = 'flex';
    updateModalPosition();
  
    if (savedEmailCooldownEnd && savedEmailCooldownEnd > now && email) {
      renderOtpVerificationUI();
    } else {
      renderEmailInputUI();
    }
  
    function renderEmailInputUI() {
      modalContent.innerHTML = `
        <span class="sign-in-close-btn">&times;</span>
        <h1 class="modal-title">Receive an OTP</h1>
        <p class="subtitle">If the email is registered, you will receive an OTP.</p>
        <form id="otp-form">
          <div class="input-group">
            <label for="otp-email">Email</label>
            <div class="input-wrapper">
              <input type="email" id="otp-email" placeholder="Enter your email" required />
              <img src="/rias/static/images/components/email_logo.png" class="input-icon" />
            </div>
            <span class="error otp-email-error"></span>
          </div>
          <button type="submit" class="btn btn-primary" id="send-otp-btn">SEND OTP</button>
          <span class="error otp-failed-error" style="display: none;"></span>
        </form>
        <p class="subtitle"><a href="#" id="back-to-sign-in">Back to Sign In</a></p>
      `;
  
      const savedCooldownEnd = parseInt(sessionStorage.getItem('otpCooldownEnd'));
      if (savedCooldownEnd && savedCooldownEnd > now) {
        activateOtpCooldown(savedCooldownEnd);
      }
  
      modalContent.querySelector('#otp-form').addEventListener('submit', (e) => {
        e.preventDefault();
        if (otpCooldown) return;
  
        const emailInput = modalContent.querySelector('#otp-email');
        const email = emailInput.value.trim();
        const errorEl = modalContent.querySelector('.otp-email-error');
        const valid = ['@student.apc.edu.ph', '@apc.edu.ph'].some(domain => email.endsWith(domain));
  
        if (!valid) {
          errorEl.textContent = 'Invalid email domain.';
          return;
        }
  
        blockClicks();
        fetch("/rias/templates/_components/sendemail.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: new URLSearchParams({ email: email, send_email: 1 })
        }).then(r => r.text()).then(() => {
          const cooldownEnd = Math.floor(Date.now() / 1000) + 300;
          sessionStorage.setItem('otpEmailCooldownEnd', cooldownEnd);
          sessionStorage.setItem('lastOtpEmail', email);
          alert("OTP sent!");
          unblockClicks();
          renderOtpVerificationUI(modalContent, email);
        }).catch(console.error);
      });
  
      modalContent.querySelector('.sign-in-close-btn').onclick = () => closeModalWithAnimation(signInModal);
      modalContent.querySelector('#back-to-sign-in').onclick = (e) => {
        e.preventDefault();
        openSignInModal();
      };
    }
  
    function renderOtpVerificationUI() {
      modalContent.innerHTML = `
        <span class="sign-in-close-btn">&times;</span>
        <h1 class="modal-title">Verify OTP</h1>
        <form id="otp-form">
          <div class="input-group">
            <label for="otp-code">Enter OTP</label>
            <div class="input-wrapper">
              <input type="text" id="otp-code" required />
              <img src="/rias/static/images/components/lock_icon.png" class="input-icon" />
            </div>
            <span class="error otp-code-error"></span>
          </div>
          <button type="submit" class="btn btn-primary" id="verify-otp-btn">VERIFY OTP</button>
          <a href="#" id="resend-otp-link" style="color: blue;">Resend OTP</a>
          <span class="error otp-failed-error" style="display: none;"></span>
        </form>
        <p class="subtitle"><a href="#" id="back-to-sign-in">Back to Sign In</a></p>
      `;
  
      modalContent.querySelector('.sign-in-close-btn').onclick = () => closeModalWithAnimation(signInModal);
      modalContent.querySelector('#back-to-sign-in').onclick = (e) => {
        e.preventDefault();
        openSignInModal();
      };
  
      const verifyBtn = modalContent.querySelector('#verify-otp-btn');
      const resendLink = modalContent.querySelector('#resend-otp-link');
      const errorMsg = modalContent.querySelector('.otp-code-error');
      const textfield = modalContent.querySelector('#otp-code');

      if (sessionStorage.getItem('resendOtp') || sessionStorage.getItem('otpCooldownEnd')) {
        resendLink.style.pointerEvents = 'none';
        resendLink.style.color = 'gray';
      }
      else {
        resendLink.style.pointerEvents = 'auto';
        resendLink.style.color = 'blue';
      }

      if (sessionStorage.getItem('otpCooldownEnd')) {
        verifyBtn.style.pointerEvents = 'none';
        verifyBtn.style.backgroundColor = 'gray';
        textfield.disabled = true;
      }
      else {
        verifyBtn.style.pointerEvents = 'auto';
        verifyBtn.style.backgroundColor = '#213B9A';
      }
  
      const cooldownEnd = parseInt(sessionStorage.getItem('otpEmailCooldownEnd'));
      if (cooldownEnd && cooldownEnd > now) {
        activateEmailCooldownTimer(verifyBtn, cooldownEnd);
      }
  
      modalContent.querySelector('#otp-form').addEventListener('submit', (e) => {
        e.preventDefault();
        blockClicks();
        verifyBtn.style.pointerEvents = 'none';
        verifyBtn.style.backgroundColor = 'gray';
        errorMsg.textContent = '';
  
        const code = modalContent.querySelector('#otp-code').value.trim();
        fetch('verifyotp.php', {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: new URLSearchParams({ otp: code, email: email })
        }).then(r => r.text()).then(data => {
          if (data.includes("verified")) {
            sessionStorage.removeItem('resendOtp');
            sessionStorage.removeItem('otpCooldownEnd');
            sessionStorage.removeItem('otpEmailCooldownEnd');
            activateEmailCooldownTimer(verifyBtn, null);
            renderOtpVerificationUI();
            alert("OTP Verified!");
            
            fetch('signin.php', {
              method: 'POST',
              body: new URLSearchParams({ otp: code, email: email })
            })
            .then(r => r.text()).then(result => {
              console.log('Login response:', result);
              result = JSON.parse(result);
              console.log('Login response:', result.status);
              if (result.status === 'success') {
                const userRole = result.role.toLowerCase().trim();
                console.log('User role:', userRole);
      
                let redirectPath;
                switch (userRole) {
                  case 'admin':
                    redirectPath = '/rias/templates/schooluser/admin/_index.php';
                    break;
                  case 'executive director':
                    redirectPath = '/rias/templates/schooluser/faculty/_index.php';
                    break;
                  case 'program director':
                    redirectPath = '/rias/templates/schooluser/faculty/_index.php';
                    break;
                  case 'internship officer':
                    redirectPath = '/rias/templates/schooluser/faculty/_index.php';
                    break;
                  case 'student intern':
                    redirectPath = '/rias/templates/schooluser/student/_index.php';
                    break;
                  default:
                    console.error('Unknown role:', userRole);
                    document.querySelector('.email-error').textContent = 'Invalid user role. Please contact support.';
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                    return;
                }
                sessionStorage.removeItem('resendOtp');
                sessionStorage.removeItem('otpEmailCooldownEnd');
                sessionStorage.removeItem('otpCooldownEnd');
                sessionStorage.removeItem('lastOtpEmail');
      
                console.log('Redirecting to:', redirectPath);
                window.location.replace(redirectPath);
              }
            });
          } else {
            unblockClicks();
            verifyBtn.style.pointerEvents = 'auto';
            verifyBtn.style.backgroundColor = '#213B9A';
            errorMsg.textContent = 'Invalid OTP.';
            if (--failedOtpAttempts <= 0) {
              errorMsg.textContent = 'Too many failed attempts.';
              startOtpCooldown();
            }
          }
        }).catch(console.error);
      });
  
      resendLink.onclick = (e) => {
        e.preventDefault();
        resendOtp(email, verifyBtn, resendLink);
      };
    }
  
    function activateEmailCooldownTimer(button, cooldownEndTime) {
      const interval = setInterval(() => {
        const now = Math.floor(Date.now() / 1000);
        const diff = cooldownEndTime - now;
        if (cooldownEndTime && diff <= 0) {
          clearInterval(interval);
          sessionStorage.removeItem('resendOtp');
          sessionStorage.removeItem('otpEmailCooldownEnd');
          button.textContent = `VERIFY OTP`;
          openOtpModal();
        } else {
          button.textContent = `VERIFY OTP (${diff}s)`;
        }
      }, 1000);
    }
  
    function startOtpCooldown() {
      const end = Math.floor(Date.now() / 1000) + 300;
      sessionStorage.setItem('otpCooldownEnd', end);
      sessionStorage.removeItem('otpEmailCooldownEnd');
  
      activateOtpCooldown(end);
    }
  
    function activateOtpCooldown(cooldownEndTime) {
      renderOtpVerificationUI();
      otpCooldown = true;
      const interval = setInterval(() => {
        const now = Math.floor(Date.now() / 1000);
        const diff = cooldownEndTime - now;
        const errorBox = modalContent.querySelector('.otp-failed-error');
        const resend = modalContent.querySelector('#resend-otp-link');
        const verifyBtn = modalContent.querySelector('#verify-otp-btn');
  
        if (diff <= 0) {
          clearInterval(interval);
          otpCooldown = false;
          sessionStorage.removeItem('resendOtp');
          sessionStorage.removeItem('otpEmailCooldownEnd');
          sessionStorage.removeItem('otpCooldownEnd');
          sessionStorage.removeItem('lastOtpEmail');
          failedOtpAttempts = 3;
          errorBox.style.display = 'none';
          resend.style.pointerEvents = 'auto';
          resend.style.color = 'blue';
          verifyBtn.disabled = false;
          verifyBtn.textContent = 'VERIFY OTP';
          openOtpModal();
          return;
        }
  
        errorBox.style.display = 'block';
        errorBox.textContent = `Too many failed attempts. Wait ${diff}s.`;
        resend.style.pointerEvents = 'none';
        resend.style.color = 'gray';
        verifyBtn.textContent = `VERIFY OTP`;
      }, 1000);
    }
  
    function resendOtp(email, verifyBtn, resendLink) {
      blockClicks();
      resendLink.textContent = 'LOADING...';
      resendLink.style.pointerEvents = 'none';
      resendLink.style.color = 'gray';
      sessionStorage.setItem('resendOtp', true);
      verifyBtn.disabled = true;
  
      fetch("/rias/templates/_components/sendemail.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ email: email, send_email: 1 })
      }).then(r => r.text()).then(() => {
        alert("OTP resent!");
        unblockClicks();
        resendLink.textContent = 'Resend OTP';
        verifyBtn.disabled = false;
  
        const cooldownEnd = Math.floor(Date.now() / 1000) + 300;
        sessionStorage.setItem('otpEmailCooldownEnd', cooldownEnd);
        activateEmailCooldownTimer(verifyBtn, cooldownEnd);
        renderOtpVerificationUI();
      }).catch(console.error);
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
            <input type="email" class="field" id="forgot-email" placeholder="Enter your email" required />
            <img src="/rias/static/images/components/email_logo.png" alt="Email Icon" class="input-icon" />
          </div>
          <span class="error forgot-email-error"></span>
        </div>
        <button type="submit" class="btn btn-primary">RESET PASSWORD</button>
        <span class="error otp-failed-error" style="display: none;"></span>
      </form>
      <p class="subtitle">
        <a href="#" id="back-to-sign-in">Back to Sign In</a>
      </p>
    `;
    
    const savedCooldownEnd = parseInt(sessionStorage.getItem('forgotPasswordCooldownEnd'));
    if (savedCooldownEnd && savedCooldownEnd > Math.floor(Date.now() / 1000)) {
      activateForgotPasswordCooldown(savedCooldownEnd);
    }
  
    // Show the modal
    signInModal.style.display = 'flex';
    updateModalPosition();
  
    // Close Forgot Password Modal
    modalContent.querySelector('.sign-in-close-btn').addEventListener('click', () => {
      closeModalWithAnimation(signInModal);
    });
  
    // Back to Sign In
    modalContent.querySelector('#back-to-sign-in').addEventListener('click', (e) => {
      e.preventDefault();
      openSignInModal();
    });
  
    // Validate and Submit Forgot Password Form
    modalContent.querySelector('#forgot-password-form').addEventListener('submit', async (e) => {
      e.preventDefault();
  
      const emailInput = document.getElementById('forgot-email');
      const email = emailInput.value.trim();
      const errorElement = modalContent.querySelector('.forgot-email-error');
      const validDomains = ['@student.apc.edu.ph', '@apc.edu.ph'];
      const isValidEmail = validDomains.some((domain) => email.endsWith(domain));
  
      if (!isValidEmail) {
        errorElement.textContent = 'Invalid email address. Use @student.apc.edu.ph or @apc.edu.ph.';
        return;
      }
  
      errorElement.textContent = '';
  
      try {
        blockClicks();
        const formData = new FormData();
        formData.append('send_email', 'true');
        formData.append('email', email);
  
        const response = await fetch('/rias/templates/schooluser/login/forgotpasswordsendemail.php', {
          method: 'POST',
          body: formData
        });
  
        const text = await response.text();
        if (response.ok) {
          console.error("text:", text);
          alert("Reset link sent successfully!\nCheck your inbox.");
          activateForgotPasswordCooldown(Date.now() / 1000 + 300);
          openSignInModal();
          unblockClicks();
        } else {
          console.error("🔴 Server error:", text);
          errorElement.textContent = 'Failed to send reset email. Something exploded. 💥';
        }
      } catch (err) {
        console.error("🐛 JS error:", err);
        errorElement.textContent = 'Unexpected error. Try again later, meatbag.';
      }
    });
  
    function activateForgotPasswordCooldown(cooldownEndTime) {
      const errorBox = modalContent.querySelector('.otp-failed-error');
      const field = modalContent.querySelector('.field');
      const btn = modalContent.querySelector('.btn-primary');
      btn.style.pointerEvents = 'none';
      btn.style.backgroundColor = 'gray';
      field.style.pointerEvents = 'none';
      field.disabled = true;
      sessionStorage.setItem('forgotPasswordCooldownEnd', cooldownEndTime);
      forgotPasswordCooldown = true;
      const interval = setInterval(() => {
        const now = Math.floor(Date.now() / 1000);
        const diff = cooldownEndTime - now;
  
        if (diff <= 0) {
          clearInterval(interval);
          forgotPasswordCooldown = false;
          sessionStorage.removeItem('forgotPasswordCooldownEnd');
          failedOtpAttempts = 3;
          errorBox.style.display = 'none';
          btn.style.pointerEvents = 'auto';
          btn.style.backgroundColor = '#213B9A';
          field.style.pointerEvents = 'auto';
          field.disabled = false;
          openSignInModal();
          return;
        }
  
        errorBox.style.display = 'block';
        errorBox.textContent = `Please wait ${diff}s before trying again.`;
        btn.style.pointerEvents = 'none';
        btn.style.backgroundColor = 'gray';
        field.style.pointerEvents = 'none';
      }, 1000);
    }
  }
  

  // Reset Hero Content Position
  function resetHeroContentPosition() {
    const heroContent = document.querySelector('.hero-content');
    heroContent.classList.remove('shift-right'); // Remove the shift-right class to reset the position
  }

  // Add float-in class to hero content on DOMContentLoaded
  if (heroContent) {
    heroContent.classList.add('float-in');
  }

  function blockClicks() {
    const blocker = document.getElementById('click-blocker');
    blocker.style.display = 'block';
  }
  
  function unblockClicks() {
    const blocker = document.getElementById('click-blocker');
    blocker.style.display = 'none';
  }
  
  const blocker = document.getElementById('click-blocker');
  
  const blockKeys = (e) => {
    if (blocker && blocker.style.display !== 'none') {
      e.preventDefault();
      e.stopPropagation();
      console.log(`Blocked key: ${e.key}`);
    }
  };
  
  window.addEventListener('keydown', blockKeys, true);
  window.addEventListener('keypress', blockKeys, true);
  window.addEventListener('keyup', blockKeys, true);
  
  blocker.style.display = 'none';
});