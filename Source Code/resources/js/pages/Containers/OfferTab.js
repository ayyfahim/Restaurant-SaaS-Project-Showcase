import React from 'react';
import ReactDOM from 'react-dom';
class OfferTab extends React.Component {
    render() {
        return (
            <div class="swiper-container swiper-offers">
            <div class="swiper-wrapper">
                <div class="swiper-slide w-auto">
                    <div class="card w-250 position-relative overflow-hidden bg-dark text-white border-0">
                        <div class="background opacity-60">
                            <img src="/assets_store/img/food1.jpg" alt="" />
                        </div>
                        <div class="card-body text-center z-1 h-50"></div>
                        <div class="card-footer border-0 z-1">
                            <div class="media">
                                <div class="media-body">
                                    <h4 class="my-0 font-weight-bold">20% Off</h4>
                                    <h6 class="mb-1">MarcDs</h6>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        );
    }
}
export default OfferTab;


