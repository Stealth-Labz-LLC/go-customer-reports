/**
 * Footer Modal - Terms, Privacy, About, Contact
 */
(function() {
  'use strict';

  const modalContent = {
    terms: {
      title: 'Terms of Service',
      body: `
        <h3>1. Acceptance of Terms</h3>
        <p>By accessing and using Videos From Santa ("the Service"), you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use the Service.</p>

        <h3>2. Description of Service</h3>
        <p>Videos From Santa provides personalized AI-generated video messages featuring Santa Claus, Mrs. Claus, and other holiday characters. Videos are created based on information you provide and delivered digitally.</p>

        <h3>3. User Responsibilities</h3>
        <ul>
          <li>You must provide accurate information for video personalization</li>
          <li>You are responsible for the content of the personalization details you submit</li>
          <li>You agree not to use the Service for any unlawful purpose</li>
          <li>You must be 18 years or older to make a purchase</li>
        </ul>

        <h3>4. Payment and Refunds</h3>
        <p>All payments are processed securely through Stripe. Due to the personalized nature of our videos, refunds are handled on a case-by-case basis. Contact support if you have concerns about your order.</p>

        <h3>5. Intellectual Property</h3>
        <p>You receive a personal, non-commercial license to use your purchased videos. You may share videos with family and friends but may not resell or redistribute them commercially.</p>

        <h3>6. Limitation of Liability</h3>
        <p>Videos From Santa provides entertainment content and makes no guarantees about specific reactions or outcomes. We are not liable for any indirect, incidental, or consequential damages.</p>

        <h3>7. Changes to Terms</h3>
        <p>We reserve the right to modify these terms at any time. Continued use of the Service after changes constitutes acceptance of the new terms.</p>
      `
    },
    privacy: {
      title: 'Privacy Policy',
      body: `
        <h3>Information We Collect</h3>
        <p>We collect information you provide directly:</p>
        <ul>
          <li>Contact information (email, phone number for SMS delivery)</li>
          <li>Payment information (processed securely by Stripe)</li>
          <li>Video personalization details (child's name, age, interests, etc.)</li>
        </ul>

        <h3>How We Use Your Information</h3>
        <ul>
          <li>To create and deliver your personalized videos</li>
          <li>To process payments and send order confirmations</li>
          <li>To communicate about your order status</li>
          <li>To improve our services</li>
        </ul>

        <h3>Data Security</h3>
        <p>We implement industry-standard security measures to protect your information. Payment data is processed through Stripe and never stored on our servers.</p>

        <h3>Data Retention</h3>
        <p>Personalization data is retained only as long as necessary to create and deliver your videos. You may request deletion of your data at any time.</p>

        <h3>Third-Party Services</h3>
        <p>We use third-party services for:</p>
        <ul>
          <li>Payment processing (Stripe)</li>
          <li>Video generation (HeyGen)</li>
          <li>Email delivery</li>
          <li>Analytics</li>
        </ul>

        <h3>Your Rights</h3>
        <p>You have the right to access, correct, or delete your personal information. Contact us at support@videosfromsanta.com for any privacy-related requests.</p>

        <h3>Contact</h3>
        <p>For privacy questions, contact: support@videosfromsanta.com</p>
      `
    },
    about: {
      title: 'About Videos From Santa',
      body: `
        <h3>Our Mission</h3>
        <p>Videos From Santa was created to bring the magic of Christmas to children everywhere through the power of personalized AI video technology.</p>

        <h3>How It Works</h3>
        <p>Using advanced AI technology, we create incredibly realistic, personalized video messages where Santa (or Mrs. Claus, or Elfie the Elf) speaks directly to your child by name, knows about their achievements, their pets, and special moments you want to celebrate.</p>

        <h3>The Magic</h3>
        <p>Every video is unique. When Santa mentions your child's name, knows about their soccer trophy, and remembers the cookies you baked together — the reaction is priceless. We've helped over 50,000 families create these magical Christmas memories.</p>

        <h3>Our Promise</h3>
        <p>We're committed to creating high-quality, heartwarming videos that preserve the wonder of Christmas. Your satisfaction and your child's joy are our top priorities.</p>
      `
    },
    contact: {
      title: 'Contact Us',
      body: `
        <h3>Customer Support</h3>
        <p>Have questions about your order or need assistance? We're here to help!</p>

        <h3>Email Support</h3>
        <p><strong>support@videosfromsanta.com</strong></p>
        <p>We typically respond within 24 hours during the holiday season.</p>

        <h3>Common Questions</h3>
        <ul>
          <li><strong>How long does delivery take?</strong> Videos are delivered within 24-48 hours.</li>
          <li><strong>Can I make changes after ordering?</strong> Contact us as soon as possible and we'll do our best to accommodate.</li>
          <li><strong>Is there a free option?</strong> Yes! Try a free 15-second video to see the quality before purchasing.</li>
        </ul>

        <h3>Business Inquiries</h3>
        <p>For partnership, affiliate, or media inquiries, please email: partners@videosfromsanta.com</p>
      `
    }
  };

  // Create modal HTML
  function createModal() {
    const overlay = document.createElement('div');
    overlay.id = 'footer-modal-overlay';
    overlay.className = 'footer-modal-overlay';
    overlay.innerHTML = `
      <div class="footer-modal">
        <div class="footer-modal-header">
          <h2 class="footer-modal-title" id="footer-modal-title"></h2>
          <button class="footer-modal-close" onclick="closeFooterModal()">&times;</button>
        </div>
        <div class="footer-modal-body" id="footer-modal-body"></div>
      </div>
    `;
    document.body.appendChild(overlay);

    // Close on overlay click
    overlay.addEventListener('click', function(e) {
      if (e.target === overlay) {
        closeFooterModal();
      }
    });

    // Close on Escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeFooterModal();
      }
    });
  }

  // Open modal
  window.openFooterModal = function(type) {
    let overlay = document.getElementById('footer-modal-overlay');
    if (!overlay) {
      createModal();
      overlay = document.getElementById('footer-modal-overlay');
    }

    const content = modalContent[type];
    if (!content) return;

    document.getElementById('footer-modal-title').textContent = content.title;
    document.getElementById('footer-modal-body').innerHTML = content.body;

    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  // Close modal
  window.closeFooterModal = function() {
    const overlay = document.getElementById('footer-modal-overlay');
    if (overlay) {
      overlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  };
})();
