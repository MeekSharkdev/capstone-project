        <button id="resend-btn" class="mt-4 w-full bg-gray-500 text-white py-2 rounded-lg disabled:opacity-50" onclick="location.reload();" <?php if (!$otp_expired) echo 'disabled'; ?>>Resend OTP</button>
        <p id="countdown" class="text-center text-sm text-gray-500 mt-2"></p>

        <script>
            <?php if (!$otp_expired) echo 'startTimer(30);'; ?>
        </script>
        </div>