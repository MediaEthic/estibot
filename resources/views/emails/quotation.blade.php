@component('mail::message')
    {{--# Votre demande de devis--}}

    {{--@component('mail::button', ['url' => ''])--}}
    {{--    Voir mon devis--}}
    {{--@endcomponent--}}


    @component('mail::panel')
        {{ $quotation->body_email }}
    @endcomponent

{{--    <div id="sig-container" style="margin-top: 15px; padding-top: 6px; border-top: 1px dashed #ddd;">--}}
{{--        <div style="float: left; margin: 2px 5px 5px 0; padding-right: 5px; display: block;" id="photoWrapper">--}}
{{--            <img src="https://www.ethicsoftware.com/wp-content/themes/ethicsoftware/images/logo-ethic-software.png"--}}
{{--                 width="65px" height="auto" id="sigPhoto">--}}
{{--        </div>--}}
{{--        <div style="margin-top:0px; margin-left: 74px;" id="sigDetailsWrapper">--}}
{{--            <p style="font-family: Helvetica, sans-serif; font-size: 12px; line-height: 14px; color: #333; margin-top:0; margin-left:0; padding-left:0;">--}}
{{--                <strong><span id="sigName">{{ $quotation->user->name }} {{ $quotation->user->surname }}</span></strong>--}}
{{--                <span id="sigTitle">/ {{ $quotation->user->profession }}</span>--}}
{{--                <br>--}}
{{--                <span>--}}
{{--                    <a href="mailto:emilie.rozis@erp-ethic.com"--}}
{{--                       id="sigEmail" style="color:#428BCA;">--}}
{{--                        {{ $quotation->user->email }}--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--                <span id="sigMobile">/ 06 45 88 11 08</span>--}}
{{--            </p>--}}
{{--            <p style="font-family: Helvetica, sans-serif; font-size: 12px; line-height: 14px; color: rgb(51, 51, 51); margin-top: 0;"--}}
{{--               id="sigCompanyWrapper">--}}
{{--                <strong><span id="sigCompany">ETHIC</span></strong><br>--}}
{{--                <span>--}}
{{--                    <a href="https://www.ethicsoftware.com/"--}}
{{--                       id="sigWebsite"--}}
{{--                       style="color:#428BCA;"--}}
{{--                       rel="nofollow">--}}
{{--                        https://www.ethicsoftware.com/--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--                <br>--}}
{{--                <span id="sigAddress"--}}
{{--                      style="font-size: 10px; line-height: 14px; margin: 5px 0; display:block; opacity:0.8;">--}}
{{--                    2 place du Réduit, 64100 BAYONNE--}}
{{--                </span>--}}
{{--            </p>--}}
{{--            <p style="font-family: Helvetica, sans-serif; font-size: 12px; line-height: 14px; color: #333;">--}}
{{--                <span id="twitterIcon" style="display: none;">--}}
{{--                    <a href="#">--}}
{{--                        <img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/twitter_circle_color-20.png"--}}
{{--                             width="20px" height="20px">--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--                <span id="facebookIcon" style="display: none;">--}}
{{--                    <a href="#">--}}
{{--                        <img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/facebook_circle_color-20.png"--}}
{{--                             width="20px" height="20px">--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--                <span id="gplusIcon" style="display: none;">--}}
{{--                    <a href="#">--}}
{{--                        <img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/google_circle_color-20.png"--}}
{{--                             width="20px" height="20px"></a></span> <span id="linkedinIcon" style="display: none;">--}}
{{--                </span>--}}
{{--                <span id="linkedinIcon" style="display: none;">--}}
{{--                    <a href="#">--}}
{{--                        <img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/linkedin_circle_color-20.png"--}}
{{--                             width="20px" height="20px">--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--                <span id="instagramIcon" style="display: none;">--}}
{{--                    <a href="#">--}}
{{--                        <img--}}
{{--                            src="https://s3.amazonaws.com/rkjha/signature-maker/icons/instagram_circle_color-20.png"--}}
{{--                            width="20px" height="20px">--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--                <span id="dribbleIcon" style="display: none;">--}}
{{--                    <a href="#">--}}
{{--                        <img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/dribbble_circle_color-20.png"--}}
{{--                             width="20px" height="20px"></a></span> <span id="youtubeIcon" style="display: none;">--}}
{{--                </span>--}}
{{--                <span id="youtubeIcon" style="display: none;">--}}
{{--                    <a href="#">--}}
{{--                        <img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/youtube_circle_color-20.png"--}}
{{--                            width="20px" height="20px">--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--                <span id="vimeoIcon" style="display: none;">--}}
{{--                    <a href="#">--}}
{{--                        <img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/vimeo_circle_color-20.png"--}}
{{--                             width="20px" height="20px"></a></span> <span id="githubIcon" style="display: none;">--}}
{{--                </span>--}}
{{--                <span id="githubIcon" style="display: none;">--}}
{{--                    <a href="#">--}}
{{--                        <img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/github_circle_black-20.png"--}}
{{--                             width="20px" height="20px">--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--                <span id="blogIcon" style="display: none;">--}}
{{--                    <a href="#">--}}
{{--                        <img src="https://s3.amazonaws.com/rkjha/signature-maker/icons/wordpress_circle_color-20.png"--}}
{{--                             width="20px" height="20px">--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--            </p>--}}
{{--        </div>--}}
{{--    </div>--}}
@endcomponent
