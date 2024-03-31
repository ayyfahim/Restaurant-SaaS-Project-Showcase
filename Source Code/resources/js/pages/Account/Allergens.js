import React from "react";
import ReactDOM from "react-dom";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import { NavLink, Route } from "react-router-dom";
import { connect } from "react-redux";
import domain from "../../config/api/domain";
import ROUTE from "../../config/route";
import FooterBar from "../Containers/FooterBar";
import NotificationSystem from "react-notification-system";
import { fetchAllergens, addAllergens } from "../../actions/authAction";
import SimpleHeader from "../Containers/SimpleHeader";

let storeId = null;

class Allergens extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            is_loading: false,
            errors: [],
            matched_allergens: [],
            fetch_message: null
        };
    }

    componentWillMount() {
        this.props.fetchAllergens();
        // this.getUserAllergens();
    }

    // getUserAllergens() {
    //     let { allergens, customer_allergens } = this.props;
    //     let matched_allergens = [];

    //     allergens.map((e1, i) => {
    //         customer_allergens.some(
    //             e2 => e2.id === e1.id && matched_allergens.push(e2.id)
    //         );
    //     });

    //     this.setState({
    //         matched_allergens: matched_allergens
    //     });
    // }

    addUserAllergens(id) {
        let { customer_allergens } = this.props;

        let find = customer_allergens.find(data => data.id == id.id);
        const newData = [];
        if (find) {
            const newData = customer_allergens.filter(
                data => data.id != find.id
            );
            console.log(`newData`, newData);

            let postData = {
                allergens: newData
            };
            this.props.addAllergens(postData);
        } else {
            const newData = [...customer_allergens, id];
            console.log(`newData`, newData);

            let postData = {
                allergens: newData
            };
            this.props.addAllergens(postData);
        }
    }

    render() {
        let { translation, allergens, customer_allergens } = this.props;
        let { matched_allergens } = this.state;
        return (
            <div>
                <NotificationSystem ref={this.notificationSystem} />
                <div
                    className="fixed-bottom-padding bg-light-gray-2 h-100-vh"
                    style={{
                        paddingBottom: 200
                    }}
                >
                    <SimpleHeader
                        text={"Allergens"}
                        goBack={this.props.history.goBack}
                    />

                    <div className="container">
                        <div className="row justify-content-center ">
                            <div className="col-12 mt-2 text-center">
                                <h5>Select Diet Preferences</h5>
                            </div>
                            <div className="col-12 mt-1">
                                <div className="bg-white clearfix edit-item">
                                    <div className="row justify-content-center py-2">
                                        {allergens.map(
                                            data =>
                                                data.type == 2 && (
                                                    <div
                                                        className="col-4"
                                                        onClick={() => {
                                                            this.addUserAllergens(
                                                                data
                                                            );
                                                        }}
                                                    >
                                                        <div className="text-center">
                                                            <img
                                                                src={`${domain.s3_url}/${
                                                                    customer_allergens.find(
                                                                        allergen =>
                                                                            allergen.id ==
                                                                            data.id
                                                                    )
                                                                        ? data.active_image_url
                                                                        : data.image_url
                                                                }`}
                                                                alt=""
                                                                className="img-fluid"
                                                                style={{
                                                                    height: 40,
                                                                    width: 40,
                                                                    display:
                                                                        "block",
                                                                    margin:
                                                                        "auto"
                                                                }}
                                                            />
                                                            {data.name}
                                                        </div>
                                                    </div>
                                                )
                                        )}
                                    </div>
                                </div>
                            </div>

                            <div className="col-12 mt-3 text-center">
                                <h5>Select Your Allergens</h5>
                            </div>
                            <div className="col-12 mt-1">
                                <div className="bg-white clearfix edit-item">
                                    <div className="row justify-content-center py-2">
                                        {allergens.map(
                                            data =>
                                                data.type == 1 && (
                                                    <div
                                                        className="col-4"
                                                        onClick={() => {
                                                            this.addUserAllergens(
                                                                data
                                                            );
                                                        }}
                                                    >
                                                        <div className="text-center">
                                                            <img
                                                                src={`/${
                                                                    customer_allergens.find(
                                                                        allergen =>
                                                                            allergen.id ==
                                                                            data.id
                                                                    )
                                                                        ? data.active_image_url
                                                                        : data.image_url
                                                                }`}
                                                                alt=""
                                                                className="img-fluid"
                                                                style={{
                                                                    height: 40,
                                                                    width: 40,
                                                                    display:
                                                                        "block",
                                                                    margin:
                                                                        "auto"
                                                                }}
                                                            />
                                                            {data.name}
                                                        </div>
                                                    </div>
                                                )
                                        )}
                                    </div>
                                </div>
                            </div>

                            <div className="col-12 mt-3 px-3">
                                <div
                                    style={{
                                        borderBottom: "1px solid #e2e2e2"
                                    }}
                                ></div>
                            </div>
                        </div>
                    </div>

                    <FooterBar translation={translation} />
                </div>
            </div>
        );
    }
}

const mapSateToProps = state => ({
    allergens: state.allergens?.allergens,
    customer_allergens: state.auth?.allergens
});

export default connect(mapSateToProps, {
    fetchAllergens,
    addAllergens
})(Allergens);
