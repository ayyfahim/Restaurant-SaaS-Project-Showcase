import React from "react";
import ROUTE from "../../config/route";
import PriceRender from "../Containers/PriceRender";
import Moment from "moment";

const Invoice = props => {
    const { order, account_info, currency, store_info } = props;
    const orderDetails = order?.order_details;

    const isOrderPaid = data => {
        return Number(data.paid_amount) >= Number(data.total);
    };

    return (
        <div className={`bg-white mb-3`}>
            {/* Invoice heading */}
            <table className="table table-borderless">
                <tbody>
                    <tr>
                        <td className="border-0">
                            <div className="row">
                                <div className="col-md text-center text-md-left mb-3 mb-md-0">
                                    <img
                                        className="logo img-fluid mb-3"
                                        src={`/${store_info?.store_logo_wide}`}
                                        style={{
                                            maxHeight: "140px"
                                        }}
                                    />
                                    <br />
                                    <h2 className="mb-1">
                                        {store_info?.store_name}
                                    </h2>
                                    {store_info?.store_address}
                                    <br />
                                </div>
                                <div className="col text-center text-md-right">
                                    {/* Dont' display Bill To on mobile */}
                                    <span className="d-none d-md-block">
                                        <h1>Billed To</h1>
                                    </span>
                                    <h4 className="mb-0">
                                        {order?.customer_name}
                                    </h4>
                                    {order?.customer_phone}
                                    <br />
                                    {order?.customer_email}
                                    <br />
                                    <h5 className="mb-0 mt-3">
                                        {Moment.utc(order?.created_at).format(
                                            "MMMM Do YYYY, h:mm:ss A"
                                        )}
                                    </h5>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            {/* Invoice items table */}
            <table className="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Item Price</th>
                        <th>QTY</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    {orderDetails?.map((orderData, key) => (
                        <tr>
                            <td>
                                <b>{orderData?.name}</b>
                                <br />

                                {orderData?.order_details_extra_addon.map(
                                    (extra, key) => (
                                        <>
                                            <span class="badge badge-primary mr-1 mb-1">
                                                {key + 1}
                                            </span>
                                            Name:{" "}
                                            <strong>
                                                {`${extra.addon_name} (${extra.addon_price})`}
                                            </strong>
                                            x
                                            <strong>{extra.addon_count}</strong>{" "}
                                            ={" "}
                                            <strong>
                                                {`$${extra.addon_count *
                                                    extra.addon_price}`}
                                            </strong>
                                            <br />
                                        </>
                                    )
                                )}
                            </td>
                            <td className="font-weight-bold align-middle text-nowrap">
                                {orderData?.price}
                            </td>
                            <td className="font-weight-bold align-middle text-nowrap">
                                {orderData?.quantity}
                            </td>
                            <td className="font-weight-bold align-middle text-nowrap color-primary">
                                {orderData?.quantity * orderData?.price}
                            </td>
                        </tr>
                    ))}

                    {/* Demo data for testing responsiveness
                    <tr>
                        <td>
                            <h5 className="mb-1">Pursuit Running Shoes</h5>
                            Men's Pursuit Running Shoes - 10/M
                        </td>
                        <td className="font-weight-bold align-middle text-nowrap">
                            20.00
                        </td>
                        <td className="font-weight-bold align-middle text-nowrap">
                            1
                        </td>
                        <td className="font-weight-bold align-middle text-nowrap">
                            $149.00 USD
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5 className="mb-1">Shelby Boots</h5>
                            Men's Shelby Leather Boots - 10/M
                        </td>
                        <td className="font-weight-bold align-middle text-nowrap">
                            99.00
                        </td>
                        <td className="font-weight-bold align-middle text-nowrap">
                            5
                        </td>
                        <td className="font-weight-bold align-middle text-nowrap">
                            $99.00 USD
                        </td>
                    </tr> 
                    */}

                    <tr>
                        <td colSpan={4} className="text-right border-0 pt-4">
                            <h6 className="text-secondary">
                                Subtotal:{" "}
                                <PriceRender
                                    currency={currency}
                                    price={order?.sub_total}
                                />
                            </h6>

                            <h6 className="text-secondary">
                                Service Charge:{" "}
                                <PriceRender
                                    currency={currency}
                                    price={order?.store_charge}
                                />
                            </h6>

                            <h6 className="text-secondary">
                                Discount: {"-("}
                                <PriceRender
                                    currency={currency}
                                    price={order?.discount}
                                />
                                {")"}
                            </h6>

                            <h5>
                                TOTAL:{" "}
                                <PriceRender
                                    currency={currency}
                                    price={order?.total}
                                />
                            </h5>
                        </td>
                    </tr>

                    {/* <tr>
                        <td colSpan={4} className="text-right border-0 pt-4">
                            <h5>Total: $248.00 USD</h5>
                        </td>
                    </tr> */}
                </tbody>
            </table>
            {/* Thank you note */}
            <h5 className="text-center py-2">Thank you for your service!</h5>
        </div>
    );
};

export default Invoice;
