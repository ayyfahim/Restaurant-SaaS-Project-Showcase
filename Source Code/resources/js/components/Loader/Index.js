import React from 'react';
import ReactDOM from 'react-dom';

class Loader extends React.Component {
render()
    {
        return (
            <div class="container-fluid pageloader">
                <div class="row h-100">
                    <div class="col-12 align-self-start text-center">
                    </div>
                    <div class="col-12 align-self-center text-center">
                        <div class="loader-logo">

                            <div class="loader-roller">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 align-self-end text-center">
                        <p class="my-5">Please wait<br /><small class="text-mute"> menu is loading...</small></p>
                    </div>
                </div>
            </div>

        );
    }
}

export default Loader;


