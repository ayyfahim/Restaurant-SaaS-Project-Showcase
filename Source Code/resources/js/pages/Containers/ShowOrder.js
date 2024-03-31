import React, { useEffect, useRef, useState } from "react";
import ReactDOM from "react-dom";
import { connect } from "react-redux";

const ShowOrder = props => {
    const order = props.order ? props.order[0] : [];
    const currency = props.currency;
    const orderDetails = order?.order_details;

    // if (!orderDetails) {
    //     console.log(`orderDetails`, orderDetails);
    //     return false;
    // }

    return (
        <>
            <div
                class="modal fade"
                id={`show-order-modal`}
                tabindex="-1"
                role="dialog"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog ">
                    {order && orderDetails && (
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    {order.order_unique_id}
                                </h5>
                                <button
                                    type="button"
                                    class="close"
                                    data-dismiss="modal"
                                    aria-label="Close"
                                    onClick={e => {
                                        $("#show-order-modal").modal("hide");
                                    }}
                                >
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Item Price</th>
                                                <th>Qty</th>
                                                <th>Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {orderDetails.map(
                                                (orderData, key) => (
                                                    <tr>
                                                        <th scope="row">
                                                            {key + 1}
                                                        </th>

                                                        <td>
                                                            <b>
                                                                {orderData.name}
                                                            </b>
                                                            <br />

                                                            {orderData.order_details_extra_addon.map(
                                                                (
                                                                    extra,
                                                                    key
                                                                ) => (
                                                                    <>
                                                                        <span class="badge badge-primary">
                                                                            {key +
                                                                                1}
                                                                        </span>
                                                                        Name:
                                                                        <strong>
                                                                            {`${extra.addon_name} ( ${extra.addon_price})`}
                                                                        </strong>
                                                                        x
                                                                        <strong>
                                                                            {" "}
                                                                            {
                                                                                extra.addon_count
                                                                            }
                                                                        </strong>{" "}
                                                                        =
                                                                        <strong>
                                                                            {" "}
                                                                            {`$${extra.addon_count *
                                                                                extra.addon_price}`}
                                                                        </strong>
                                                                        <br />
                                                                    </>
                                                                )
                                                            )}
                                                        </td>
                                                        <td>
                                                            {orderData.price}
                                                        </td>
                                                        <td>
                                                            {orderData.quantity}
                                                        </td>
                                                        <td class="color-primary">
                                                            {" "}
                                                            {orderData.quantity *
                                                                orderData.price}
                                                        </td>
                                                    </tr>
                                                )
                                            )}
                                        </tbody>
                                    </table>
                                </div>
                                <br />
                                <div class="float-right">
                                    <table class="table table-bordered table-striped bill-calc-table">
                                        <tbody>
                                            <tr>
                                                <td class="text-left td-title">
                                                    SubTotal
                                                </td>
                                                <td class="td-data">
                                                    <price>{`${currency} ${order.sub_total}`}</price>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-left td-title">
                                                    Service Charge
                                                </td>
                                                <td class="td-data">
                                                    {" "}
                                                    <price>{`${currency} ${order.store_charge}`}</price>
                                                </td>
                                            </tr>

                                            {/* 
                                            <tr>
                                                <td class="text-left td-title">
                                                    Tax
                                                </td>
                                                <td class="td-data">
                                                    <price>{`${currency} ${order.tax}`}</price>
                                                </td>
                                            </tr> 
                                            */}

                                            <tr>
                                                <td class="text-left td-title">
                                                    Discount
                                                </td>
                                                <td class="td-data">
                                                    <price>{`- (${currency} ${order.discount})`}</price>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-left td-title">
                                                    <b>TOTAL</b>
                                                </td>
                                                <td class="td-data">
                                                    {" "}
                                                    <price>{`${currency} ${order.total}`}</price>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {/* 
                    <div class="modal-footer p-0 border-0 fixed-bottom">
                        <div class="col-6 m-0 p-0">
                            <button
                                type="button"
                                class="btn btn-dark btn-lg btn-block"
                                data-dismiss="modal"
                            >
                                {"Close"}
                            </button>
                        </div>
                        <div class="col-6 m-0 p-0">
                            <button
                                type="button"
                                data-dismiss="modal"
                                onClick={() =>
                                    saveAddon(
                                        addon[0]?.product_id,
                                        addon[0]?.categories[0].type
                                    )
                                }
                                class="btn btn-danger btn-lg btn-block"
                            >
                                {translation?.menu_save_changes ||
                                    "Save changes"}{" "}
                            </button>
                        </div>
                    </div> 
                    */}
                        </div>
                    )}
                </div>
                <button
                    style={{ visibility: "hidden" }}
                    type="button"
                    id={`#show-order-modal`}
                    class="btn btn-outline-success btn-sm ml-auto"
                    data-toggle="modal"
                    data-target={`#show-order-modal`}
                >
                    Add
                </button>
            </div>
        </>
    );
};

// export default ShowOrder;

const mapSateToProps = state => ({
    order: state.checkout.orders
});

export default connect(mapSateToProps, {})(ShowOrder);
