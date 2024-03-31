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
import SimpleHeader from "../Containers/SimpleHeader";
import { Widget } from "@typeform/embed-react";

let storeId = null;

class Support extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {};
    }

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

                    <Widget
                        id="zD2YBe1n"
                        style={{ width: "100%" }}
                        className="my-form"
                    />

                    <div className="container">
                        <div className="row justify-content-center mt-5">
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
    // all_Translation: state.translation?.languages,
    // active_language_id: state.translation?.active?.id
});

export default connect(mapSateToProps, {
    // fetchTranslation,
    // fetchAllTranslation
})(Support);
