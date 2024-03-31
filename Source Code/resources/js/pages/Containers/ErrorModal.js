import React from "react";
import { connect } from "react-redux";

class ErrorModal extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div
                class="modal fade"
                id={`errorModal`}
                tabindex="-1"
                role="dialog"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div
                            class="modal-body"
                            style={{
                                textAlign: "center",
                                paddingTop: "50px"
                            }}
                        >
                            <h2>{this.props.text}</h2>
                            <img
                                src="/images/error.gif"
                                class="img-fluid mt-3"
                            />
                        </div>
                    </div>
                </div>
                <button
                    style={{ visibility: "hidden" }}
                    type="button"
                    id={`#errorModal`}
                    class="btn btn-outline-success btn-sm ml-auto"
                    data-toggle="modal"
                    data-target={`#errorModal`}
                >
                    Add
                </button>
            </div>
        );
    }
}

export default ErrorModal;

// const mapSateToProps = state => ({
//     // store_name: state.store.store_name,
//     // description: state.store.description,
//     // sliders: state.store.sliders,
//     // recommendedItems: state.store.recommendedItems,
//     // account_info: state.store.account_info,
//     // categories: state.store.categories,
//     // products: state.store.products,
//     // cart: state.cart.Items,
//     // orders: state.orders.Orders,
//     // tables: state.store.tables,
//     // addons: state.store.addons,
//     name: state.auth.userName,
//     phone: state.auth.userPhone
// });

// export default connect(mapSateToProps, { callTheWaiter })(CallTheWaiter);
