@component('mail::message')
# Deposit Confirmed ✅

Hello **{{ $name }}**,  

Your deposit of **R$ {{ $amount }}** has been successfully processed. 🎉  

## Transaction Details:
- **Wallet ID:** {{ $walletId }}
- **Amount:** R$ {{ $amount }}
- **Status:** Confirmed ✅

@component('mail::button', ['url' => '#'])
View Transaction
@endcomponent

If you have any questions, feel free to contact our support team.

Thank you for choosing **PirasWallet**! 🚀  

Best regards,  
**The PirasWallet Team**
@endcomponent
