@component('mail::message')
# Transaction Reversed ✅

Hello **{{ $name }}**,  

Your transaction of **R$ {{ $amount }}** has been successfully reversed. 🎉  

## Transaction Details:
- **Transaction ID:** {{ $transactionId }}
- **Amount:** R$ {{ $amount }}
- **Status:** Reversed ✅

@component('mail::button', ['url' => '#'])
View Transaction
@endcomponent

If you have any questions, feel free to contact our support team.

Thank you for choosing **PirasWallet**! 🚀  

Best regards,  
**The PirasWallet Team**
@endcomponent
