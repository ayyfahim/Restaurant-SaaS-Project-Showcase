import React from "react";
import ReactDOM from "react-dom";
import domain from "../../config/api/domain";

class Slider extends React.Component {
    render() {
        const { sliders, translation } = this.props;
        return sliders && sliders.length ? (
            <div class="py-3 osahan-promos shadow-sm">
                <div class="d-flex align-items-center px-3 mb-2">
                    <h6 class="m-0">
                        {translation?.menu_promo || "Promos for you"}{" "}
                    </h6>
                </div>
                <div class="promo-slider">
                    {sliders
                        ? sliders.map(data => (
                              <div class="osahan-slider-item m-2">
                                  <a>
                                      <img
                                          src={`${domain.url}/${data.photo_url}`}
                                          class="img-fluid mx-auto rounded shadow-sm"
                                          style={{
                                              width: "100%",
                                              height: "100%"
                                          }}
                                          alt="Responsive image"
                                      />
                                  </a>
                              </div>
                          ))
                        : null}
                </div>
            </div>
        ) : null;

        // <a class="slider-wrapper__img-wrapper"  style={{position:"relative"}}>
        //     <img src={`${domain.url}/${photo}`} alt="" class="slider-wrapper__img slider-cust-img slider-wrapper__img-shadow custom-promo-img" style={{height: "12rem", width: "22rem"}} />
        // </a>
    }
}

export default Slider;
