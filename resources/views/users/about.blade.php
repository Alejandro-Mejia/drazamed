@include('...header')

    <div class="contact-container">
        <div class="contact-inner container">
            <div class="col-sm-3">
                <h1 class="contact-h1">About {{ Setting::param('site','app_name')['value'] }}</h1>
            </div>
            <div class="col-sm-9 contact-right-section about-cont">
                <div class="contact-right-upper">
                    <div class="contact-upp-inn">
                    <h2 class="contact-h2">{{ __('Buy Medicines Online.. Its easy as it’s Name')}}</h2>

                    <p>{{ Setting::param('site','app_name')['value'] }} {{ __('is an online medicine provider')}}. {{ __('We have started this with an aim to facilitate the purchase of medicines effortlessly')}}. {{ __('No more waiting in front of medicine shops')}}, {{ __('the customers will get the prescribed medicines at their doorsteps either through self delivery or courier service')}}. "{{ __('The customers will avail a discount of about 13% on almost every medicine')}}". {{ __('All the drugs supplied by us will have long shelf life and a valid invoice will be provided along with the delivered medicines')}}. </p>


                    </div>
                    <p class="contact-h2 btn btn-primary save-btn" style="color:#fff;cursor:default;">How it works?</p>

                    <ul class="contact-ul">
                        <li>{{ __('Initially upload us your prescription of the required medicines')}}.  </li>
                        <li>{{ __('Else select online from the list of medicines and add it to your cart')}}.
</li>
                        <li>Our pharmacists will identify, examine and update your prescription in the very next moment.
</li>
                        <li>Verify and make the payment, you will be notified regarding status of the shipment.</li>
                    </ul>

                    <p>We made it simple and trouble free for you to connect with us for making yourpurchase of medicine online.</p>

                    <!--<button type="button" class="btn btn-primary save-btn ripple" data-color="#40E0BC" style="margin-top:30px;">Read More</button>-->

                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <footer>
        <div class="container innerBtm">

@include('...footer')