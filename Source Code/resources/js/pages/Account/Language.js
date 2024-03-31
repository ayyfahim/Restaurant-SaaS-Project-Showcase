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
import { updateCustomer, refreshUser } from "../../actions/authAction";
import {
    fetchTranslation,
    fetchAllTranslation
} from "../../actions/translationAction";
import SimpleHeader from "../Containers/SimpleHeader";

let storeId = null;

class Language extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            is_loading: false,
            errors: [],
            fetch_message: null
        };
    }

    change_language = id => {
        this.props.fetchTranslation({ language_id: id });
        localStorage.setItem("active_language_id", id);
    };

    render() {
        let { translation, all_Translation, active_language_id } = this.props;
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
                        text={"Language"}
                        goBack={this.props.history.goBack}
                    />

                    <div className="container">
                        <div className="row justify-content-center mt-5">
                            {all_Translation &&
                                all_Translation.map(data => (
                                    <div className="col-12 mt-3">
                                        <div
                                            className="bg-white clearfix edit-item"
                                            onClick={() =>
                                                this.change_language(data.id)
                                            }
                                        >
                                            <div className="float-left">
                                                <h6 className="m-0">
                                                    {data.language_name}
                                                </h6>
                                            </div>
                                            <div className="float-right">
                                                <div
                                                    className={`px-1 d-inline-block ${
                                                        active_language_id ==
                                                        data.id
                                                            ? "text-success"
                                                            : "text-muted"
                                                    }`}
                                                >
                                                    <i class="icofont-check-circled"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}

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
    all_Translation: state.translation?.languages,
    active_language_id: state.translation?.active?.id
});

export default connect(mapSateToProps, {
    fetchTranslation,
    fetchAllTranslation
})(Language);
