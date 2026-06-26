<x-guest-layout>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-white">Create Account</h2>
        <p class="text-xs text-slate-400 mt-1">Start investing with Global Interactive Markets today</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{
        open: false,
        search: '',
        selectedCountry: { name: 'United States', code: 'US', currency: 'USD', symbol: '$', flag: '🇺🇸' },
        countries: [
            { name: 'Afghanistan', code: 'AF', currency: 'AFN', symbol: '؋', flag: '🇦🇫' },
            { name: 'Albania', code: 'AL', currency: 'ALL', symbol: 'L', flag: '🇦🇱' },
            { name: 'Algeria', code: 'DZ', currency: 'DZD', symbol: 'د.ج', flag: '🇩🇿' },
            { name: 'Andorra', code: 'AD', currency: 'EUR', symbol: '€', flag: '🇦🇩' },
            { name: 'Angola', code: 'AO', currency: 'AOA', symbol: 'Kz', flag: '🇦🇴' },
            { name: 'Argentina', code: 'AR', currency: 'ARS', symbol: '$', flag: '🇦🇷' },
            { name: 'Armenia', code: 'AM', currency: 'AMD', symbol: '֏', flag: '🇦🇲' },
            { name: 'Australia', code: 'AU', currency: 'AUD', symbol: '$', flag: '🇦🇺' },
            { name: 'Austria', code: 'AT', currency: 'EUR', symbol: '€', flag: '🇦🇹' },
            { name: 'Azerbaijan', code: 'AZ', currency: 'AZN', symbol: '₼', flag: '🇦🇿' },
            { name: 'Bahamas', code: 'BS', currency: 'BSD', symbol: '$', flag: '🇧🇸' },
            { name: 'Bahrain', code: 'BH', currency: 'BHD', symbol: '.د.ب', flag: '🇧🇭' },
            { name: 'Bangladesh', code: 'BD', currency: 'BDT', symbol: '৳', flag: '🇧🇩' },
            { name: 'Barbados', code: 'BB', currency: 'BBD', symbol: '$', flag: '🇧🇧' },
            { name: 'Belarus', code: 'BY', currency: 'BYN', symbol: 'Br', flag: '🇧🇾' },
            { name: 'Belgium', code: 'BE', currency: 'EUR', symbol: '€', flag: '🇧🇪' },
            { name: 'Belize', code: 'BZ', currency: 'BZD', symbol: '$', flag: '🇧🇿' },
            { name: 'Benin', code: 'BJ', currency: 'XOF', symbol: 'Fr', flag: '🇧🇯' },
            { name: 'Bhutan', code: 'BT', currency: 'BTN', symbol: 'Nu.', flag: '🇧🇹' },
            { name: 'Bolivia', code: 'BO', currency: 'BOB', symbol: 'Bs.', flag: '🇧🇴' },
            { name: 'Bosnia and Herzegovina', code: 'BA', currency: 'BAM', symbol: 'KM', flag: '🇧🇦' },
            { name: 'Botswana', code: 'BW', currency: 'BWP', symbol: 'P', flag: '🇧🇼' },
            { name: 'Brazil', code: 'BR', currency: 'BRL', symbol: 'R$', flag: '🇧🇷' },
            { name: 'Brunei', code: 'BN', currency: 'BND', symbol: '$', flag: '🇧🇳' },
            { name: 'Bulgaria', code: 'BG', currency: 'BGN', symbol: 'лв', flag: '🇧🇬' },
            { name: 'Burkina Faso', code: 'BF', currency: 'XOF', symbol: 'Fr', flag: '🇧🇫' },
            { name: 'Burundi', code: 'BI', currency: 'BIF', symbol: 'Fr', flag: '🇧🇮' },
            { name: 'Cambodia', code: 'KH', currency: 'KHR', symbol: '៛', flag: '🇰🇭' },
            { name: 'Cameroon', code: 'CM', currency: 'XAF', symbol: 'Fr', flag: '🇨🇲' },
            { name: 'Canada', code: 'CA', currency: 'CAD', symbol: '$', flag: '🇨🇦' },
            { name: 'Central African Republic', code: 'CF', currency: 'XAF', symbol: 'Fr', flag: '🇨🇫' },
            { name: 'Chad', code: 'TD', currency: 'XAF', symbol: 'Fr', flag: '🇹🇩' },
            { name: 'Chile', code: 'CL', currency: 'CLP', symbol: '$', flag: '🇨🇱' },
            { name: 'China', code: 'CN', currency: 'CNY', symbol: '¥', flag: '🇨🇳' },
            { name: 'Colombia', code: 'CO', currency: 'COP', symbol: '$', flag: '🇨🇴' },
            { name: 'Comoros', code: 'KM', currency: 'KMF', symbol: 'Fr', flag: '🇰🇲' },
            { name: 'Congo', code: 'CG', currency: 'XAF', symbol: 'Fr', flag: '🇨🇬' },
            { name: 'Costa Rica', code: 'CR', currency: 'CRC', symbol: '₡', flag: '🇨🇷' },
            { name: 'Croatia', code: 'HR', currency: 'EUR', symbol: '€', flag: '🇭🇷' },
            { name: 'Cuba', code: 'CU', currency: 'CUP', symbol: '$', flag: '🇨🇺' },
            { name: 'Cyprus', code: 'CY', currency: 'EUR', symbol: '€', flag: '🇨🇾' },
            { name: 'Czech Republic', code: 'CZ', currency: 'CZK', symbol: 'Kč', flag: '🇨🇿' },
            { name: 'Denmark', code: 'DK', currency: 'DKK', symbol: 'kr', flag: '🇩🇰' },
            { name: 'Djibouti', code: 'DJ', currency: 'DJF', symbol: 'Fr', flag: '🇩🇯' },
            { name: 'Dominica', code: 'DM', currency: 'XCD', symbol: '$', flag: '🇩🇲' },
            { name: 'Dominican Republic', code: 'DO', currency: 'DOP', symbol: '$', flag: '🇩🇴' },
            { name: 'Ecuador', code: 'EC', currency: 'USD', symbol: '$', flag: '🇪🇨' },
            { name: 'Egypt', code: 'EG', currency: 'EGP', symbol: '£', flag: '🇪🇬' },
            { name: 'El Salvador', code: 'SV', currency: 'USD', symbol: '$', flag: '🇸🇻' },
            { name: 'Estonia', code: 'EE', currency: 'EUR', symbol: '€', flag: '🇪🇪' },
            { name: 'Ethiopia', code: 'ET', currency: 'ETB', symbol: 'Br', flag: '🇪🇹' },
            { name: 'Fiji', code: 'FJ', currency: 'FJD', symbol: '$', flag: '🇫🇯' },
            { name: 'Finland', code: 'FI', currency: 'EUR', symbol: '€', flag: '🇫🇮' },
            { name: 'France', code: 'FR', currency: 'EUR', symbol: '€', flag: '🇫🇷' },
            { name: 'Gabon', code: 'GA', currency: 'XAF', symbol: 'Fr', flag: '🇬🇦' },
            { name: 'Gambia', code: 'GM', currency: 'GMD', symbol: 'D', flag: '🇬🇲' },
            { name: 'Georgia', code: 'GE', currency: 'GEL', symbol: '₾', flag: '🇬🇪' },
            { name: 'Germany', code: 'DE', currency: 'EUR', symbol: '€', flag: '🇩🇪' },
            { name: 'Ghana', code: 'GH', currency: 'GHS', symbol: '₵', flag: '🇬🇭' },
            { name: 'Greece', code: 'GR', currency: 'EUR', symbol: '€', flag: '🇬🇷' },
            { name: 'Guatemala', code: 'GT', currency: 'GTQ', symbol: 'Q', flag: '🇬🇹' },
            { name: 'Guinea', code: 'GN', currency: 'GNF', symbol: 'Fr', flag: '🇬🇳' },
            { name: 'Guyana', code: 'GY', currency: 'GYD', symbol: '$', flag: '🇬🇾' },
            { name: 'Haiti', code: 'HT', currency: 'HTG', symbol: 'G', flag: '🇭🇹' },
            { name: 'Honduras', code: 'HN', currency: 'HNL', symbol: 'L', flag: '🇭🇳' },
            { name: 'Hong Kong', code: 'HK', currency: 'HKD', symbol: '$', flag: '🇭🇰' },
            { name: 'Hungary', code: 'HU', currency: 'HUF', symbol: 'Ft', flag: '🇭🇺' },
            { name: 'Iceland', code: 'IS', currency: 'ISK', symbol: 'kr', flag: '🇮🇸' },
            { name: 'India', code: 'IN', currency: 'INR', symbol: '₹', flag: '🇮🇳' },
            { name: 'Indonesia', code: 'ID', currency: 'IDR', symbol: 'Rp', flag: '🇮🇩' },
            { name: 'Iran', code: 'IR', currency: 'IRR', symbol: '﷼', flag: '🇮🇷' },
            { name: 'Iraq', code: 'IQ', currency: 'IQD', symbol: 'ع.د', flag: '🇮🇶' },
            { name: 'Ireland', code: 'IE', currency: 'EUR', symbol: '€', flag: '🇮🇪' },
            { name: 'Israel', code: 'IL', currency: 'ILS', symbol: '₪', flag: '🇮🇱' },
            { name: 'Italy', code: 'IT', currency: 'EUR', symbol: '€', flag: '🇮🇹' },
            { name: 'Jamaica', code: 'JM', currency: 'JMD', symbol: '$', flag: '🇯🇲' },
            { name: 'Japan', code: 'JP', currency: 'JPY', symbol: '¥', flag: '🇯🇵' },
            { name: 'Jordan', code: 'JO', currency: 'JOD', symbol: 'د.ا', flag: '🇯🇴' },
            { name: 'Kazakhstan', code: 'KZ', currency: 'KZT', symbol: '₸', flag: '🇰🇿' },
            { name: 'Kenya', code: 'KE', currency: 'KES', symbol: 'Sh', flag: '🇰🇪' },
            { name: 'Kuwait', code: 'KW', currency: 'KWD', symbol: 'د.ك', flag: '🇰🇼' },
            { name: 'Kyrgyzstan', code: 'KG', currency: 'KGS', symbol: 'с', flag: '🇰🇬' },
            { name: 'Laos', code: 'LA', currency: 'LAK', symbol: '₭', flag: '🇱🇦' },
            { name: 'Latvia', code: 'LV', currency: 'EUR', symbol: '€', flag: '🇱🇻' },
            { name: 'Lebanon', code: 'LB', currency: 'LBP', symbol: 'ل.ل', flag: '🇱🇧' },
            { name: 'Lesotho', code: 'LS', currency: 'LSL', symbol: 'L', flag: '🇱🇸' },
            { name: 'Liberia', code: 'LR', currency: 'LRD', symbol: '$', flag: '🇱🇷' },
            { name: 'Libya', code: 'LY', currency: 'LYD', symbol: 'ل.د', flag: '🇱🇾' },
            { name: 'Liechtenstein', code: 'LI', currency: 'CHF', symbol: 'Fr', flag: '🇱🇮' },
            { name: 'Lithuania', code: 'LT', currency: 'EUR', symbol: '€', flag: '🇱🇹' },
            { name: 'Luxembourg', code: 'LU', currency: 'EUR', symbol: '€', flag: '🇱🇺' },
            { name: 'Macau', code: 'MO', currency: 'MOP', symbol: 'P', flag: '🇲🇴' },
            { name: 'Macedonia', code: 'MK', currency: 'MKD', symbol: 'ден', flag: '🇲🇰' },
            { name: 'Madagascar', code: 'MG', currency: 'MGA', symbol: 'Ar', flag: '🇲🇬' },
            { name: 'Malawi', code: 'MW', currency: 'MWK', symbol: 'MK', flag: '🇲🇼' },
            { name: 'Malaysia', code: 'MY', currency: 'MYR', symbol: 'RM', flag: '🇲🇾' },
            { name: 'Maldives', code: 'MV', currency: 'MVR', symbol: 'Rf', flag: '🇲🇻' },
            { name: 'Mali', code: 'ML', currency: 'XOF', symbol: 'Fr', flag: '🇲🇱' },
            { name: 'Malta', code: 'MT', currency: 'EUR', symbol: '€', flag: '🇲🇹' },
            { name: 'Mauritania', code: 'MR', currency: 'MRU', symbol: 'UM', flag: '🇲🇷' },
            { name: 'Mauritius', code: 'MU', currency: 'MUR', symbol: '₨', flag: '🇲🇺' },
            { name: 'Mexico', code: 'MX', currency: 'MXN', symbol: '$', flag: '🇲🇽' },
            { name: 'Moldova', code: 'MD', currency: 'MDL', symbol: 'L', flag: '🇲🇩' },
            { name: 'Monaco', code: 'MC', currency: 'EUR', symbol: '€', flag: '🇲🇨' },
            { name: 'Mongolia', code: 'MN', currency: 'MNT', symbol: '₮', flag: '🇲🇳' },
            { name: 'Montenegro', code: 'ME', currency: 'EUR', symbol: '€', flag: '🇲🇪' },
            { name: 'Morocco', code: 'MA', currency: 'MAD', symbol: 'د.م.', flag: '🇲🇦' },
            { name: 'Mozambique', code: 'MZ', currency: 'MZN', symbol: 'MT', flag: '🇲🇿' },
            { name: 'Myanmar', code: 'MM', currency: 'MMK', symbol: 'Ks', flag: '🇲🇲' },
            { name: 'Namibia', code: 'NA', currency: 'NAD', symbol: '$', flag: '🇳🇦' },
            { name: 'Nepal', code: 'NP', currency: 'NPR', symbol: '₨', flag: '🇳🇵' },
            { name: 'Netherlands', code: 'NL', currency: 'EUR', symbol: '€', flag: '🇳🇱' },
            { name: 'New Zealand', code: 'NZ', currency: 'NZD', symbol: '$', flag: '🇳🇿' },
            { name: 'Nicaragua', code: 'NI', currency: 'NIO', symbol: 'C$', flag: '🇳🇮' },
            { name: 'Niger', code: 'NE', currency: 'XOF', symbol: 'Fr', flag: '🇳🇪' },
            { name: 'Nigeria', code: 'NG', currency: 'NGN', symbol: '₦', flag: '🇳🇬' },
            { name: 'Norway', code: 'NO', currency: 'NOK', symbol: 'kr', flag: '🇳🇴' },
            { name: 'Oman', code: 'OM', currency: 'OMR', symbol: 'ر.ع.', flag: '🇴🇲' },
            { name: 'Pakistan', code: 'PK', currency: 'PKR', symbol: '₨', flag: '🇵🇰' },
            { name: 'Palestine', code: 'PS', currency: 'ILS', symbol: '₪', flag: '🇵🇸' },
            { name: 'Panama', code: 'PA', currency: 'PAB', symbol: 'B/.', flag: '🇵🇦' },
            { name: 'Papua New Guinea', code: 'PG', currency: 'PGK', symbol: 'K', flag: '🇵🇬' },
            { name: 'Paraguay', code: 'PY', currency: 'PYG', symbol: '₲', flag: '🇵🇾' },
            { name: 'Peru', code: 'PE', currency: 'PEN', symbol: 'S/.', flag: '🇵🇪' },
            { name: 'Philippines', code: 'PH', currency: 'PHP', symbol: '₱', flag: '🇵🇭' },
            { name: 'Poland', code: 'PL', currency: 'PLN', symbol: 'zł', flag: '🇵🇱' },
            { name: 'Portugal', code: 'PT', currency: 'EUR', symbol: '€', flag: '🇵🇹' },
            { name: 'Qatar', code: 'QA', currency: 'QAR', symbol: 'ر.ق', flag: '🇶🇦' },
            { name: 'Romania', code: 'RO', currency: 'RON', symbol: 'lei', flag: '🇷🇴' },
            { name: 'Russia', code: 'RU', currency: 'RUB', symbol: '₽', flag: '🇷🇺' },
            { name: 'Rwanda', code: 'RW', currency: 'RWF', symbol: 'Fr', flag: '🇷🇼' },
            { name: 'Saudi Arabia', code: 'SA', currency: 'SAR', symbol: 'ر.س', flag: '🇸🇦' },
            { name: 'Senegal', code: 'SN', currency: 'XOF', symbol: 'Fr', flag: '🇸🇳' },
            { name: 'Serbia', code: 'RS', currency: 'RSD', symbol: 'дин.', flag: '🇷🇸' },
            { name: 'Singapore', code: 'SG', currency: 'SGD', symbol: '$', flag: '🇸🇬' },
            { name: 'Slovakia', code: 'SK', currency: 'EUR', symbol: '€', flag: '🇸🇰' },
            { name: 'Slovenia', code: 'SI', currency: 'EUR', symbol: '€', flag: '🇸🇮' },
            { name: 'Somalia', code: 'SO', currency: 'SOS', symbol: 'Sh', flag: '🇸🇴' },
            { name: 'South Africa', code: 'ZA', currency: 'ZAR', symbol: 'R', flag: '🇿🇦' },
            { name: 'South Korea', code: 'KR', currency: 'KRW', symbol: '₩', flag: '🇰🇷' },
            { name: 'Spain', code: 'ES', currency: 'EUR', symbol: '€', flag: '🇪🇸' },
            { name: 'Sri Lanka', code: 'LK', currency: 'LKR', symbol: 'Rs', flag: '🇱🇰' },
            { name: 'Sudan', code: 'SD', currency: 'SDG', symbol: '🇸🇩' },
            { name: 'Suriname', code: 'SR', currency: 'SRD', symbol: '$', flag: '🇸🇷' },
            { name: 'Sweden', code: 'SE', currency: 'SEK', symbol: 'kr', flag: '🇸🇪' },
            { name: 'Switzerland', code: 'CH', currency: 'CHF', symbol: 'Fr', flag: '🇨🇭' },
            { name: 'Syria', code: 'SY', currency: 'SYP', symbol: '£', flag: '🇸🇾' },
            { name: 'Taiwan', code: 'TW', currency: 'TWD', symbol: '$', flag: '🇹🇼' },
            { name: 'Tajikistan', code: 'TJ', currency: 'TJS', symbol: 'ЅМ', flag: '🇹🇯' },
            { name: 'Tanzania', code: 'TZ', currency: 'TZS', symbol: 'Sh', flag: '🇹🇿' },
            { name: 'Thailand', code: 'TH', currency: 'THB', symbol: '฿', flag: '🇹🇭' },
            { name: 'Timor-Leste', code: 'TL', currency: 'USD', symbol: '$', flag: '🇹🇱' },
            { name: 'Togo', code: 'TG', currency: 'XOF', symbol: 'Fr', flag: '🇹🇬' },
            { name: 'Trinidad and Tobago', code: 'TT', currency: 'TTD', symbol: '$', flag: '🇹🇹' },
            { name: 'Tunisia', code: 'TN', currency: 'TND', symbol: 'د.ت', flag: '🇹🇳' },
            { name: 'Turkey', code: 'TR', currency: 'TRY', symbol: '₺', flag: '🇹🇷' },
            { name: 'Turkmenistan', code: 'TM', currency: 'TMT', symbol: 'm', flag: '🇹🇲' },
            { name: 'Uganda', code: 'UG', currency: 'UGX', symbol: 'Sh', flag: '🇺🇬' },
            { name: 'Ukraine', code: 'UA', currency: 'UAH', symbol: '₴', flag: '🇺🇦' },
            { name: 'United Arab Emirates', code: 'AE', currency: 'AED', symbol: 'د.إ', flag: '🇦🇪' },
            { name: 'United Kingdom', code: 'GB', currency: 'GBP', symbol: '£', flag: '🇬🇧' },
            { name: 'United States', code: 'US', currency: 'USD', symbol: '$', flag: '🇺🇸' },
            { name: 'Uruguay', code: 'UY', currency: 'UYU', symbol: '$', flag: '🇺🇾' },
            { name: 'Uzbekistan', code: 'UZ', currency: 'UZS', symbol: 'so\'m', flag: '🇺🇿' },
            { name: 'Vanuatu', code: 'VU', currency: 'VUV', symbol: 'Vt', flag: '🇻🇺' },
            { name: 'Venezuela', code: 'VE', currency: 'VES', symbol: 'Bs.S', flag: '🇻🇪' },
            { name: 'Vietnam', code: 'VN', currency: 'VND', symbol: '₫', flag: '🇻🇳' },
            { name: 'Yemen', code: 'YE', currency: 'YER', symbol: '﷼', flag: '🇾🇪' },
            { name: 'Zambia', code: 'ZM', currency: 'ZMW', symbol: 'ZK', flag: '🇿🇲' },
            { name: 'Zimbabwe', code: 'ZW', currency: 'ZWL', symbol: '$', flag: '🇿🇼' }
        ],
        init() {
            let oldCountry = '{{ old('country_name') }}';
            if (oldCountry) {
                let found = this.countries.find(c => c.name === oldCountry);
                if (found) this.selectedCountry = found;
            }
        },
        select(country) {
            this.selectedCountry = country;
            this.open = false;
            this.search = '';
        },
        filteredCountries() {
            if (!this.search) return this.countries;
            return this.countries.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
        }
    }">
        @csrf

        <!-- Name -->
        <div class="space-y-1">
            <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none"
                placeholder="John Doe">
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none"
                placeholder="john@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Phone Number -->
        <div class="space-y-1">
            <label for="phone" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Phone Number</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none"
                placeholder="+1 234 567 890">
            <x-input-error :messages="$errors->get('phone')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Country Selection -->
        <div class="space-y-1">
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Country of Residence</label>
            
            <div class="relative">
                <!-- Dropdown Trigger -->
                <button type="button" @click="open = !open"
                    class="relative w-full px-3 py-2.5 rounded-lg text-sm auth-input text-left focus:outline-none flex justify-between items-center bg-slate-900 border border-slate-800 text-white cursor-pointer select-none">
                    <span class="flex items-center gap-2">
                        <span class="text-base" x-text="selectedCountry.flag"></span>
                        <span x-text="selectedCountry.name"></span>
                    </span>
                    <i class="fa fa-chevron-down text-slate-400 text-xs"></i>
                </button>

                <!-- Hidden inputs for validation / controller -->
                <input type="hidden" name="country_name" :value="selectedCountry.name">
                <input type="hidden" name="country_code" :value="selectedCountry.code">

                <!-- Dropdown List -->
                <div x-show="open" @click.outside="open = false" x-transition
                    class="absolute z-50 mt-1 w-full rounded-lg bg-slate-950 border border-slate-800 shadow-2xl max-h-60 overflow-hidden flex flex-col">
                    
                    <!-- Search Input -->
                    <div class="p-2 border-b border-slate-800 bg-slate-900 flex items-center gap-2">
                        <i class="fa fa-search text-slate-400 text-xs"></i>
                        <input type="text" x-model="search" placeholder="Type to search country..."
                            class="w-full bg-transparent text-xs text-white border-none p-1 focus:outline-none focus:ring-0 placeholder-slate-500">
                    </div>

                    <!-- Scrollable List -->
                    <div class="overflow-y-auto flex-grow divide-y divide-slate-800/40">
                        <template x-for="country in filteredCountries()" :key="country.code">
                            <button type="button" @click="select(country)"
                                class="w-full px-3 py-2 text-left text-xs hover:bg-teal-600 hover:text-white text-slate-300 flex items-center gap-2.5 transition select-none cursor-pointer">
                                <span class="text-sm" x-text="country.flag"></span>
                                <span x-text="country.name"></span>
                                <span class="text-[10px] text-slate-500 ml-auto font-mono" x-text="country.currency"></span>
                            </button>
                        </template>
                        <div x-show="filteredCountries().length === 0" class="p-4 text-center text-xs text-slate-500">
                            No countries found.
                        </div>
                    </div>
                </div>
            </div>
            <x-input-error :messages="$errors->get('country_name')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Currency Display (Auto-filled) -->
        <div class="space-y-1">
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Default Currency (Auto-assigned)</label>
            <div class="flex items-center gap-2">
                <div class="w-full relative">
                    <input type="text" name="currency_code" readonly :value="selectedCountry.currency"
                        class="block w-full px-3 py-2.5 rounded-lg text-sm bg-slate-950/60 border border-slate-800 text-teal-400 font-extrabold focus:outline-none cursor-not-allowed">
                    <span class="absolute inset-y-0 right-3 flex items-center text-xs text-slate-500 font-mono" x-text="'Symbol: ' + selectedCountry.symbol"></span>
                </div>
                <input type="hidden" name="currency_symbol" :value="selectedCountry.symbol">
            </div>
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none"
                placeholder="Min. 8 characters">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <label for="password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="block w-full px-3 py-2.5 rounded-lg text-sm auth-input focus:outline-none"
                placeholder="Repeat password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Register Button -->
        <button type="submit" class="btn-primary w-full py-3 mt-4 text-sm flex items-center justify-center gap-2 shadow-lg">
            <i class="fa fa-user-plus"></i>
            <span>Register Account</span>
        </button>

        <!-- Redirect to Login -->
        <div class="text-center pt-4 border-t border-slate-800 text-xs text-slate-400">
            Already registered? 
            <a href="{{ route('login') }}" class="text-teal-400 hover:text-teal-300 font-bold transition">
                Log In Here
            </a>
        </div>
    </form>
</x-guest-layout>
