/**
 * AJAX Contact Form Submission Handler
 */

document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contactForm');
    const alertBox = document.getElementById('contactAlert');
    const submitBtn = document.getElementById('contactSubmitBtn');

    if (!contactForm) return;

    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Hide previous alert
        alertBox.style.display = 'none';
        alertBox.className = 'alert-message';
        
        // Change submit button state
        const originalBtnHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Sending...';
        submitBtn.disabled = true;

        const formData = new FormData(contactForm);

        try {
            const response = await fetch('api/contact.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alertBox.className = 'alert-message alert-success';
                alertBox.textContent = result.message || 'Thank you! Your message has been sent successfully.';
                alertBox.style.display = 'block';
                contactForm.reset();
            } else {
                alertBox.className = 'alert-message alert-error';
                alertBox.textContent = result.message || 'Failed to send message. Please try again.';
                alertBox.style.display = 'block';
            }
        } catch (error) {
            alertBox.className = 'alert-message alert-error';
            alertBox.textContent = 'An error occurred while connecting to the server. Please try again later.';
            alertBox.style.display = 'block';
        } finally {
            submitBtn.innerHTML = originalBtnHtml;
            submitBtn.disabled = false;
        }
    });
});
