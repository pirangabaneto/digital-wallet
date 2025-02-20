@component('mail::message')
# Transfer Confirmed ✅

Hello **{{ $name }}**,  

Your transfer of **R$ {{ $amount }}** has been successfully processed. 🎉  

## Transaction Details:
- **From Wallet ID:** {{ $fromWalletId }}
- **To Wallet ID:** {{ $toWalletId }}
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
