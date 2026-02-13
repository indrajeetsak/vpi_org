<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PaymentModel; // You'll need to create this model

class PaymentController extends BaseController
{
    protected $session;
    protected $userModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new UserModel();
        // $this->paymentModel = new PaymentModel(); // Uncomment when model is created

        // Ensure user is logged in for all payment actions
        if (!$this->session->get('isLoggedIn')) {
            // Allow redirect to login, but might need a better way to handle this for a controller
            // For now, this will work if routes are filtered.
            // Consider throwing an exception or specific handling if filter is not applied.
            return redirect()->to('auth/login');
        }
    }

    public function initiate()
    {
        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user || $user['status'] !== 'pending_payment') {
            session()->setFlashdata('error', 'Invalid state for payment initiation.');
            return redirect()->to('dashboard');
        }
        
        // Fetch Appointment Details (Post, Level, Organ names)
        // Even though status is pending_payment, the appointment record exists (likely status=pending or pending_payment)
        $appointmentModel = new \App\Models\AppointmentModel();
        $appt = $appointmentModel->getDetailsByUserId($userId);
        if ($appt) {
            $user = array_merge($user, $appt);
        }

        $data['user'] = $user;
        $data['payment_amount'] = 500.00; // Example amount

        // Here you would generate a unique order ID and save a pending payment record
        // $orderId = 'VPI_ORD_' . $userId . '_' . time();
        // $this->paymentModel->insert([
        //     'user_id' => $userId,
        //     'order_id' => $orderId,
        //     'amount' => $data['payment_amount'],
        //     'payment_status' => 'pending',
        // ]);
        // $data['order_id'] = $orderId;

        return view('payment/initiate_payment', $data);
    }

    public function initiateCCAvenue()
    {
        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);
        $orderId = $this->request->getPost('order_id'); // Or generate new one
        $amount = $this->request->getPost('amount');

        // TODO: Implement CCAvenue integration logic
        // This involves preparing data, encrypting it, and redirecting to CCAvenue
        // For example:
        // $ccavenueHelper = new \App\Libraries\CCAvenueHelper(); // Your CCAvenue library
        // $encryptedData = $ccavenueHelper->encrypt([...]);
        // $data['encrypted_data'] = $encryptedData;
        // $data['access_code'] = 'YOUR_ACCESS_CODE';
        // $data['ccavenue_url'] = 'CCA VENUE_POST_URL';
        // return view('payment/ccavenue_redirect', $data);

        // For now, simulating a redirect to success for testing flow
        session()->setFlashdata('info', 'CCAvenue redirection would happen here. Simulating success.');
        // Simulate updating payment and user status
        // $this->paymentModel->where('order_id', $orderId)->set(['payment_status' => 'success', 'transaction_id' => 'SIM_TRANS_123'])->update();
        $this->userModel->update($userId, ['status' => 'active']); // Or 'pending_approval' if admin needs to approve after payment
        return redirect()->to('payment/success?order_id=' . ($orderId ?? 'SIM_ORD_123') . '&tracking_id=SIM_TRACK_123&bank_ref_no=SIM_BANK_123&amount=' . ($amount ?? '500'));
    }

    public function ccavenueResponseHandler()
    {
        // TODO: Implement CCAvenue response decryption and processing
        // $workingKey = 'YOUR_WORKING_KEY';
        // $encResponse = $this->request->getPost('encResp');
        // $rcvdString = decrypt($encResponse, $workingKey); // Your decryption logic
        // $order_status = ""; // Extract from $rcvdString
        // $order_id = ""; // Extract

        // if ($order_status === "Success") {
        //     $this->userModel->update($userIdBasedOnOrderId, ['status' => 'active']);
        //     $this->paymentModel->update($order_id, ['payment_status' => 'success', ...]);
        //     return redirect()->to('payment/success?order_id=' . $order_id . '&...');
        // } else {
        //     return redirect()->to('payment/failure?order_id=' . $order_id . '&...');
        // }
        return redirect()->to('dashboard'); // Fallback
    }

    public function paymentSuccess()
    {
        $data['order_id'] = $this->request->getGet('order_id') ?? 'N/A';
        $data['tracking_id'] = $this->request->getGet('tracking_id') ?? 'N/A';
        $data['bank_ref_no'] = $this->request->getGet('bank_ref_no') ?? 'N/A';
        $data['amount_paid'] = $this->request->getGet('amount') ?? 'N/A';
        $data['user'] = $this->userModel->find(session()->get('user_id'));
        return view('payment/payment_success', $data);
    }

    public function paymentFailure()
    {
        $data['order_id'] = $this->request->getGet('order_id') ?? 'N/A';
        $data['reason'] = $this->request->getGet('reason') ?? 'Payment was not completed.';
        return view('payment/payment_failure', $data);
    }
}