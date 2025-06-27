@extends('layouts.main')
@section('content')
    <div id="content">
        <div class="slider-block style-three">
            <div class="slider-main">
                <div class="slider-item">
                    <div class="container">
                        <div class="row flex-between item-start gap-32">
                            <div class="left-block flex-column row-gap-40 col-7">
                                <div class="heading2 text-white animate__animated animate__fadeInUp animate__delay-0-2s">Banking made easy with <br>our online platform</div>
                                <div class="button-block animate__animated animate__fadeInUp animate__delay-0-5s"><a class="button-share display-inline-flex hover-bg-white bg-gradient text-white text-button pl-28 pr-28 pt-16 pb-16 bora-8 flex-item-center gap-8" href="{{ route('register') }}"><i class="ph ph-arrow-right text-white"></i><span>Get Stared</span></a>
                                </div>
                            </div>
                            <div class="right-block flex-column row-gap-32 col-5 animate__animated animate__fadeInRight animate__delay-0-2s">
                                <div class="body2 text-placehover">Experience the convenience and simplicity of banking from anywhere with our user-friendly online platform. Manage your accounts, transfer funds, pay bills, and more with just a few clicks.</div>
                                <div class="count flex-between gap-24">
                                    <div class="left">
                                        <div class="heading3 text-white">1.77k</div>
                                        <div class="body1 text-white mt-4">Business Problem Solving</div>
                                    </div>
                                    <div class="right">
                                        <div class="heading3 text-white">246k</div>
                                        <div class="body1 text-white mt-4">Passive income earners</div>
                                    </div>
                                </div>
                            </div>
                            <div class="slider-img col-11"><img class="w-100 animate__animated animate__fadeInUp animate__delay-1s" src="{{ asset('frontend/images/slider/graphic-three.png') }}" alt=""/></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="list-benefit-three mt-60">
            <div class="container">
                <div class="row row-gap-60">
                    <div class="col-12 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="service-item hover-box-shadow pl-32 pr-32 box-shadow-none"><a class="service-item-main flex-column gap-16" href="#!">
                                <div class="heading flex-between"><i class="icon-hand-touch icon-gradient fs-42"></i>
                                    <div class="number heading3 text-placehover"> </div>
                                </div>
                                <div class="desc">
                                    <div class="heading7 hover-text-blue">Convenience</div>
                                    <div class="body3 text-secondary mt-4">Experience the convenience of banking with easy access to your accounts anytime, anywhere.</div>
                                </div></a>
                        </div>
                    </div>
                    <div class="col-12 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="service-item hover-box-shadow pl-32 pr-32 box-shadow-none"><a class="service-item-main flex-column gap-16" href="#!">
                                <div class="heading flex-between"><i class="icon-user-lock icon-gradient fs-42"></i>
                                    <div class="number heading3 text-placehover"> </div>
                                </div>
                                <div class="desc">
                                    <div class="heading7 hover-text-blue">24/7 account access</div>
                                    <div class="body3 text-secondary mt-4">Rest assured with our robust security measures to protect your transactions and sensitive.</div>
                                </div></a>
                        </div>
                    </div>
                    <div class="col-12 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="service-item hover-box-shadow pl-32 pr-32 box-shadow-none"><a class="service-item-main flex-column gap-16" href="#!">
                                <div class="heading flex-between"><i class="icon-coin-bag icon-gradient fs-42"></i>
                                    <div class="number heading3 text-placehover"> </div>
                                </div>
                                <div class="desc">
                                    <div class="heading7 hover-text-blue"> safe transactions</div>
                                    <div class="body3 text-secondary mt-4">Stay in control of your finances with effortless tracking and monitoring of your transactions.</div>
                                </div></a>
                        </div>
                    </div>
                    <div class="col-12 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="service-item hover-box-shadow pl-32 pr-32 box-shadow-none"><a class="service-item-main flex-column gap-16" href="#!">
                                <div class="heading flex-between"><i class="icon-chart-blue icon-gradient fs-42"></i>
                                    <div class="number heading3 text-placehover"> </div>
                                </div>
                                <div class="desc">
                                    <div class="heading7 hover-text-blue">Easily track finances</div>
                                    <div class="body3 text-secondary mt-4">Enjoy round-the-clock access to your accounts for tracking seamless financial management.</div>
                                </div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="style-three">
            <div class=" layout-item mt-100">
                <div class="container">
                    <div class="row row-gap-32">
                        <div class="col-12 col-lg-6 pr-40 flex-column justify-content-center">
                            <div class="heading3">Registering and Using Online Banking Services</div>
                            <div class="body2 text-secondary mt-20">Online banking allows you to manage your finances from anywhere, anytime. You can access your bank account, check your balance, view transactions, and transfer money without having to visit a physical bank.</div>
                            <div class="button-block mt-24"><a class="button-share bg-gradient hover-button-black text-white text-button display-inline-block pt-12 pb-12 pl-28 pr-28 bora-8 flex-item-center gap-8" href="contact-two.html"><i class="ph-bold ph-arrow-right text-white fs-20"></i><span>discovery</span></a>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 pl-55">
                            <div class="bg-video w-100 overflow-hidden bora-20"><img class="w-100 display-block" src="{{ asset('frontend/images/blog/item2.png') }}" alt=""/><i class="ph-fill ph-play fs-28 bg-white bora-50"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="service-block style-about-two mt-100 pt-60 pb-60 bg-surface style-three">
            <div class="container">
                <div class="heading-block row flex-columns-center">
                    <div class="col-12 heading3 text-center">Our Services</div>
                    <div class="col-10 text-center body2 text-secondary mt-12">Online banking allows you to manage your finances from anywhere, anytime.</div>
                </div>
                <div class="list-service mt-40 pt-12">
                    <div class="row row-gap-40">
                        <div class="col-12 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="service-item hover-box-shadow pl-32 pr-32 pt-40 pb-40 bora-8 bg-white"><a class="service-item-main flex-column gap-16" href="service-education-resources.html">
                                    <div class="heading flex-item-center gap-16"><i class="icon-hand-tick icon-gradient fs-60"></i>
                                        <div class="heading6 hover-text-blue">Internet Banking</div>
                                    </div>
                                    <div class="body3 text-secondary">With our Internet Banking service, you can access your accounts anytime, anywhere.</div></a>
                            </div>
                        </div>
                        <div class="col-12 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="service-item hover-box-shadow pl-32 pr-32 pt-40 pb-40 bora-8 bg-white"><a class="service-item-main flex-column gap-16" href="service-education-resources.html">
                                    <div class="heading flex-item-center gap-16"><i class="icon-coin-chair icon-gradient fs-60"></i>
                                        <div class="heading6 hover-text-blue">Mobile Banking</div>
                                    </div>
                                    <div class="body3 text-secondary">Our Mobile Banking app brings you fast and convenient mobile financial experience.</div></a>
                            </div>
                        </div>
                        <div class="col-12 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="service-item hover-box-shadow pl-32 pr-32 pt-40 pb-40 bora-8 bg-white"><a class="service-item-main flex-column gap-16" href="service-education-resources.html">
                                    <div class="heading flex-item-center gap-16"><i class="icon-coin-hand icon-gradient fs-60"></i>
                                        <div class="heading6 hover-text-blue">Money Transfers</div>
                                    </div>
                                    <div class="body3 text-secondary">Our money transfer service allows you to send money quickly to anywhere in the world.</div></a>
                            </div>
                        </div>
                        <div class="col-12 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="service-item hover-box-shadow pl-32 pr-32 pt-40 pb-40 bora-8 bg-white"><a class="service-item-main flex-column gap-16" href="service-education-resources.html">
                                    <div class="heading flex-item-center gap-16"><i class="icon-hand-protect icon-gradient fs-60"></i>
                                        <div class="heading6 hover-text-blue">Account Management</div>
                                    </div>
                                    <div class="body3 text-secondary">Managing your accounts has never been easier with Online Banking. Check your balances.</div></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="case-studies-block style-two style-three mt-100">
            <div class="container">
                <div class="heading flex-between pb-40">
                    <div class="heading3">Case Study</div>
                    <div class="list-nav flex-center gap-8">
                        <div class="nav-item text-button-small text-secondary pt-8 pb-8 pl-20 pr-20 pointer" data-name="investing">Investing</div>
                        <div class="nav-item text-button-small text-secondary pt-8 pb-8 pl-20 pr-20 pointer active" data-name="fintech">Fintech</div>
                        <div class="nav-item text-button-small text-secondary pt-8 pb-8 pl-20 pr-20 pointer" data-name="crypto">Crypto</div>
                        <div class="nav-item text-button-small text-secondary pt-8 pb-8 pl-20 pr-20 pointer" data-name="blockchain">Blockchain</div>
                        <div class="nav-item text-button-small text-secondary pt-8 pb-8 pl-20 pr-20 pointer" data-name="planning">Planning</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-4 col-sm-6 item-filter" data-name="fintech">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item4.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Retirement Planning Strategies</div>
                                <div class="body2 text-secondary mt-8">Made Financial Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter" data-name="fintech">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item5.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Tax Optimization Solutions</div>
                                <div class="body2 text-secondary mt-8">Retirement Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter" data-name="fintech">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item6.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Business Succession Planning</div>
                                <div class="body2 text-secondary mt-8">Account management tools</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter" data-name="fintech">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item3.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Education Funding Strategies</div>
                                <div class="body2 text-secondary mt-8">Retirement Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter" data-name="fintech">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item2.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Portfolio Management</div>
                                <div class="body2 text-secondary mt-8">Account management tools</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="investing">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item1.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Retirement Planning Strategies</div>
                                <div class="body2 text-secondary mt-8">Made Financial Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="investing">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item7.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Education Funding Strategies</div>
                                <div class="body2 text-secondary mt-8">Retirement Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="investing">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item8.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Business Succession Planning</div>
                                <div class="body2 text-secondary mt-8">Account management tools</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="investing">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item9.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Tax Optimization Solutions</div>
                                <div class="body2 text-secondary mt-8">Retirement Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="investing">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item10.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Portfolio Management</div>
                                <div class="body2 text-secondary mt-8">Account management tools</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="crypto">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item11.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Retirement Planning Strategies</div>
                                <div class="body2 text-secondary mt-8">Made Financial Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="crypto">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item12.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Tax Optimization Solutions</div>
                                <div class="body2 text-secondary mt-8">Retirement Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="crypto">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item7.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Portfolio Management</div>
                                <div class="body2 text-secondary mt-8">Account management tools</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="crypto">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item9.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Education Funding Strategies</div>
                                <div class="body2 text-secondary mt-8">Retirement Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="crypto">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item4.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Business Succession Planning</div>
                                <div class="body2 text-secondary mt-8">Account management tools</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="blockchain">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item8.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Retirement Planning Strategies</div>
                                <div class="body2 text-secondary mt-8">Made Financial Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="blockchain">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item10.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Education Funding Strategies</div>
                                <div class="body2 text-secondary mt-8">Retirement Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="blockchain">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item6.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Business Succession Planning</div>
                                <div class="body2 text-secondary mt-8">Account management tools</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="blockchain">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item2.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Tax Optimization Solutions</div>
                                <div class="body2 text-secondary mt-8">Retirement Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="blockchain">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item5.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Portfolio Management</div>
                                <div class="body2 text-secondary mt-8">Account management tools</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="planning">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item1.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Retirement Planning Strategies</div>
                                <div class="body2 text-secondary mt-8">Made Financial Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="planning">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item4.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Tax Optimization Solutions</div>
                                <div class="body2 text-secondary mt-8">Retirement Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="planning">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item10.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Portfolio Management</div>
                                <div class="body2 text-secondary mt-8">Account management tools</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="planning">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item9.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Education Funding Strategies</div>
                                <div class="body2 text-secondary mt-8">Retirement Planning</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-sm-6 item-filter hide" data-name="planning">
                        <div class="item-main">
                            <div class="bg-img overflow-hidden"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item8.png') }}" alt=""/><a class="flex-columns-center pt-32 pb-32 pl-20 pr-20 bg-white bora-50 text-center" href="case-studies-detail.html">
                                    <div class="text-button-small text-gradient">Discovery</div><i class="ph-bold ph-arrow-up-right text-gradient"></i></a></div>
                            <div class="infor bg-white bora-8 pt-24">
                                <div class="heading6">Business Succession Planning</div>
                                <div class="body2 text-secondary mt-8">Account management tools</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="features-three bg-surface mt-100 pt-60 pb-60">
            <div class="container">
                <div class="row flex-between">
                    <div class="col-12 col-xl-6 pr-80">
                        <div class="heading">
                            <div class="heading3">Features </div>
                            <div class="body2 text-secondary mt-20">Online banking allows you to manage your finances from anywhere, anytime. You can access your bank account, check your balance, view transactions, and transfer money without having to visit a physical bank.</div>
                        </div>
                        <div class="list-nav-item mt-40">
                            <div class="nav-item heading7 pt-24 pb-24 pl-20" data-name="bill-payment">Bill payment and transfer options</div>
                            <div class="nav-item heading7 pt-24 pb-24 pl-20 active" data-name="account-balance">Account balance and transaction history</div>
                            <div class="nav-item heading7 pt-24 pb-24 pl-20" data-name="mobile-banking">Mobile banking and alerts</div>
                            <div class="nav-item heading7 pt-24 pb-24 pl-20" data-name="account-management">Account management tools</div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-5 content-filter" data-name="account-balance">
                        <div class="bg-img"> <img src="{{ asset('frontend/images/component/graphic-features3.png') }}" alt=""/></div>
                        <div class="infor bg-white pt-24 pb-24 bora-12">
                            <div class="infor-user pl-32 pr-32 pb-24">
                                <div class="heading flex-item-center gap-16"> <img class="w-60 h-60" src="{{ asset('frontend/images/member/avatar5.png') }}" alt=""/>
                                    <div class="desc">
                                        <div class="text-button-small fw-600 text-gradient">UI UX Designer</div>
                                        <div class="name flex-item-center gap-8 mt-4">
                                            <div class="heading7">Maverick Nguyen</div><i class="ph-fill ph-lightning text-white bg-gradient fs-12 p-4 bora-50"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-32 flex-between">
                                    <div class="heading7 text-secondary">Account balance</div>
                                    <div class="text-button">$110,000</div>
                                </div>
                            </div>
                            <div class="line-x"></div>
                            <div class="pt-24 history pl-32 pr-32">
                                <div class="desc">
                                    <div class="text-button-small fw-600 text-gradient">Transaction History</div>
                                    <div class="name flex-item-center gap-8 mt-4">
                                        <div class="heading7">Maverick Nguyen</div><i class="ph-fill ph-lightning text-white bg-gradient fs-12 p-4 bora-50"></i>
                                    </div>
                                    <div class="mt-16 flex-between">
                                        <div class="text-button">$400,000</div>
                                        <div class="text-button">- $110,000</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-5 content-filter hide" data-name="bill-payment">
                        <div class="bg-img"> <img src="{{ asset('frontend/images/component/graphic-features3.png') }}" alt=""/></div>
                        <div class="infor overflow-hidden bora-20"><img class="w-100 bora-20 hover-scale display-block" src="{{ asset('frontend/images/blog/item9.png') }}" alt=""/></div>
                    </div>
                    <div class="col-12 col-xl-5 content-filter hide" data-name="mobile-banking">
                        <div class="bg-img"> <img src="{{ asset('frontend/images/component/graphic-features3.png') }}" alt=""/></div>
                        <div class="infor overflow-hidden bora-20"><img class="w-100 bora-20 hover-scale display-block" src="{{ asset('frontend/images/blog/item10.png') }}" alt=""/></div>
                    </div>
                    <div class="col-12 col-xl-5 content-filter hide" data-name="account-management">
                        <div class="bg-img"> <img src="{{ asset('frontend/images/component/graphic-features3.png') }}" alt=""/></div>
                        <div class="infor overflow-hidden bora-20"><img class="w-100 bora-20 hover-scale display-block" src="{{ asset('frontend/images/blog/item11.png') }}" alt=""/></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="testimonials-three mt-100">
            <div class="container">
                <div class="heading3 text-center">What People Are Saying</div>
                <div class="list-comment row">
                    <div class="col-12 col-lg-4 col-sm-6 comment-item">
                        <div class="item p-32 bg-white bora-12 box-shadow hover-box-shadow">
                            <div class="body3 text-secondary">"Working with this agency has been a game-changer for our business. Their team is knowledgeable, responsive, and always goes the extra mile."</div>
                            <div class="infor mt-16 flex-item-center gap-16">
                                <div class="avatar"><img class="w-60 h-60" src="{{ asset('frontend/images/member/avatar1.png') }}" alt=""/></div>
                                <div class="desc">
                                    <div class="text-button">Maverick</div>
                                    <div class="caption2 text-secondary mt-4">Chariman, Avitex Inc</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 col-sm-6 comment-item">
                        <div class="item p-32 bg-white bora-12 box-shadow hover-box-shadow">
                            <div class="body3 text-secondary">"Your personalized approach and care have improved my financial planning. I highly value your services and attention to detail in crafting financial plans."</div>
                            <div class="infor mt-16 flex-item-center gap-16">
                                <div class="avatar"><img class="w-60 h-60" src="{{ asset('frontend/images/member/avatar3.png') }}" alt=""/></div>
                                <div class="desc">
                                    <div class="text-button">Alexander</div>
                                    <div class="caption2 text-secondary mt-4">Chariman, Avitex Inc</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 col-sm-6 comment-item">
                        <div class="item p-32 bg-white bora-12 box-shadow hover-box-shadow">
                            <div class="body3 text-secondary">"I'm extremely satisfied with your services! Your meticulous financial planning helped me manage my assets efficiently and achieve my financial goals."</div>
                            <div class="infor mt-16 flex-item-center gap-16">
                                <div class="avatar"><img class="w-60 h-60" src="{{ asset('frontend/images/member/avatar2.png') }}" alt=""/></div>
                                <div class="desc">
                                    <div class="text-button">De Rossi</div>
                                    <div class="caption2 text-secondary mt-4">Chariman, Avitex Inc</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 col-sm-6 comment-item">
                        <div class="item p-32 bg-white bora-12 box-shadow hover-box-shadow">
                            <div class="body3 text-secondary">"I highly value and appreciate your services and the attention to detail you provide in crafting financial plans. Thank you for helping shape my financial future!"</div>
                            <div class="infor mt-16 flex-item-center gap-16">
                                <div class="avatar"><img class="w-60 h-60" src="{{ asset('frontend/images/member/avatar4.png') }}" alt=""/></div>
                                <div class="desc">
                                    <div class="text-button">Benjamin</div>
                                    <div class="caption2 text-secondary mt-4">Chariman, Avitex Inc</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-cta-block benefit-three mt-100 bg-surface">
            <div class="container h-100">
                <div class="row flex-between h-100 row-gap-20">
                    <div class="col-12 col-lg-6">
                        <div class="heading3">Bank online with ease and security. Take control of your finances with a few clicks.</div>
                        <div class="heading7 text-secondary mt-16">Schedule a consultation now.</div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="form-block bora-16 bg-white p-28 flex-columns-between gap-20">
                            <div class="heading6">Need Help?</div>
                            <div class="row row-gap-20">
                                <div class="col-12 col-sm-6">
                                    <input class="w-100 bg-surface caption1 pl-16 pr-16 pt-12 pb-12 bora-8" type="text" placeholder="Name"/>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input class="w-100 bg-surface caption1 pl-16 pr-16 pt-12 pb-12 bora-8" type="text" placeholder="Email"/>
                                </div>
                                <div class="col-12">
                                    <select class="w-100 bg-surface caption1 pl-12 pt-12 pb-12 bora-8" name="categories">
                                        <option value="Financial Planning">Financial Planning</option>
                                        <option value="Business Planning">Business Planning</option>
                                        <option value="Development Planning">Development Planning</option>
                                    </select><i class="ph ph-caret-down"></i>
                                </div>
                                <div class="col-12">
                                    <textarea class="w-100 bg-surface caption1 pl-16 pr-16 pt-12 pb-12 bora-8" name="messsage" cols="10" rows="4" placeholder="Your Message"></textarea>
                                </div>
                            </div>
                            <div class="button-block">
                                <button class="button-share hover-bg-gradient bg-on-surface text-white text-button pl-36 pr-36 pt-12 pb-12 bora-48">Send Message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="style-three">
            <div class="blog-list style-one mt-100">
                <div class="container">
                    <div class="heading3 text-center">Latest News</div>
                    <div class="row row-gap-32 mt-40">
                        <div class="blog-item col-12 col-xl-4 col-sm-6" data-name=""><a class="blog-item-main" href="blog-detail-two.html">
                                <div class="bg-img w-100 overflow-hidden mb-minus-1"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item11.png') }}" alt="CI Financial sells RIA stake in new expansion strategy"/></div>
                                <div class="infor bg-white p-24">
                                    <div class="caption2 pt-4 pb-4 pl-12 pr-12 bg-surface bora-40 display-inline-block">Makerting</div>
                                    <div class="heading6 mt-8">CI Financial sells RIA stake in new expansion strategy</div>
                                    <div class="date flex-item-center gap-16 mt-8">
                                        <div class="author caption2 text-secondary">by <span class="text-on-surface">Avitex</span></div>
                                        <div class="item-date flex-item-center"><i class="ph-bold ph-calendar-blank"></i><span class="ml-4 caption2">1 days ago</span></div>
                                    </div>
                                </div></a></div>
                        <div class="blog-item col-12 col-xl-4 col-sm-6" data-name=""><a class="blog-item-main" href="blog-detail-two.html">
                                <div class="bg-img w-100 overflow-hidden mb-minus-1"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item13.png') }}" alt="Barred financial advisors charged in $72 million criminal"/></div>
                                <div class="infor bg-white p-24">
                                    <div class="caption2 pt-4 pb-4 pl-12 pr-12 bg-surface bora-40 display-inline-block">Development</div>
                                    <div class="heading6 mt-8">Barred financial advisors charged in $72 million criminal</div>
                                    <div class="date flex-item-center gap-16 mt-8">
                                        <div class="author caption2 text-secondary">by <span class="text-on-surface">Avitex</span></div>
                                        <div class="item-date flex-item-center"><i class="ph-bold ph-calendar-blank"></i><span class="ml-4 caption2">2 days ago</span></div>
                                    </div>
                                </div></a></div>
                        <div class="blog-item col-12 col-xl-4 col-sm-6" data-name=""><a class="blog-item-main" href="blog-detail-two.html">
                                <div class="bg-img w-100 overflow-hidden mb-minus-1"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item12.png') }}" alt="Retirement Planning Strategies"/></div>
                                <div class="infor bg-white p-24">
                                    <div class="caption2 pt-4 pb-4 pl-12 pr-12 bg-surface bora-40 display-inline-block">Design</div>
                                    <div class="heading6 mt-8">Retirement Planning Strategies</div>
                                    <div class="date flex-item-center gap-16 mt-8">
                                        <div class="author caption2 text-secondary">by <span class="text-on-surface">Avitex</span></div>
                                        <div class="item-date flex-item-center"><i class="ph-bold ph-calendar-blank"></i><span class="ml-4 caption2">2 days ago</span></div>
                                    </div>
                                </div></a></div>
                        <div class="blog-item col-12 col-xl-4 col-sm-6 display-none col-lg-show" data-name=""><a class="blog-item-main" href="blog-detail-two.html">
                                <div class="bg-img w-100 overflow-hidden mb-minus-1"><img class="w-100 h-100 display-block" src="{{ asset('frontend/images/blog/item10.png') }}" alt="Helping a local business"/></div>
                                <div class="infor bg-white p-24">
                                    <div class="caption2 pt-4 pb-4 pl-12 pr-12 bg-surface bora-40 display-inline-block">Makerting</div>
                                    <div class="heading6 mt-8">Helping a local business</div>
                                    <div class="date flex-item-center gap-16 mt-8">
                                        <div class="author caption2 text-secondary">by <span class="text-on-surface">Avitex</span></div>
                                        <div class="item-date flex-item-center"><i class="ph-bold ph-calendar-blank"></i><span class="ml-4 caption2">3 days ago</span></div>
                                    </div>
                                </div></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="js-video-modal">
            <div class="js-video-modal-container">
                <div class="video-block">
                    <iframe src="https://www.youtube.com/embed/RaQKTgGyyyo" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen="allowfullscreen"></iframe>
                </div>
            </div>
        </div>
        <div class="style-three"><a class="scroll-to-top-btn" href="#header"><i class="ph-bold ph-caret-up"></i></a></div>
        <div class="pb-100"></div>
    </div>
@endsection
