import React from "react";
import { connect } from "react-redux";

class CallTheWaiterButton extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div
                id={`call-the-waiter-button`}
                onClick={() =>
                    document.getElementById("#call-the-waiter").click()
                }
            >
                <img
                    src={`/images/icons/store/waiter_red.png`}
                    style={{ maxHeight: 40 }}
                    class="m-auto"
                />
            </div>
        );
    }
}

export default CallTheWaiterButton;
