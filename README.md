# Laravel Webhook Handler Microservice

This repository contains a Laravel-based microservice to handle webhooks. Follow the steps below to set up and run the project.

## Installation Guide

### 1. Clone the Project
```sh
git clone https://github.com/mayur1805/scaletech-stripe-demo.git
cd your-project
```

### 2. Set Up Laravel Project
```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### 3. Add Stripe and GitHub Variables in .env
Edit the `.env` file and add your Stripe API credentials:
```
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret
STRIPE_WEBHOOK_SECRET=your_webhook_secret
GITHUB_WEBHOOK_SECRET=your_github_webhook_secret 
```

### 4. Install Ngrok
[Download and install Ngrok](https://ngrok.com/download) if you haven't already.

### 5. Start Ngrok Server
Run the following command to start Ngrok on port 8000:
```sh
ngrok http 8000
```
Copy the generated HTTPS URL (e.g., `https://your-ngrok-url.ngrok.io`).

### 6. Set Up Webhook on Stripe
- Go to your [Stripe Dashboard](https://dashboard.stripe.com/)
- Navigate to **Developers > Webhooks**
- Click **Add endpoint** and paste the Ngrok URL followed by your webhook route, e.g., `https://your-ngrok-url.ngrok.io/webhook`
- Select the required events and save
```sh
payment_intent.created
payment_intent.succeeded
payment_intent.payment_failed
payment_intent.canceled
```

### 7. Set Up Webhook on GitHub
- Go to your GitHub repository.
- Click on Settings → Webhooks (in the left sidebar).
- Click the "Add webhook" button.
- Click **Add endpoint** and paste the Ngrok URL followed by your webhook route, e.g., `https://your-ngrok-url.ngrok.io/webhook`
- Content type:
  ```sh
application/json
```

- Use a secret string (e.g., your_github_secret) and store it in your .env as GITHUB_SECRET.

### 7. Test the Application
Run the Laravel server:
```sh
php artisan serve
```
## License
This project is licensed under the MIT License.
