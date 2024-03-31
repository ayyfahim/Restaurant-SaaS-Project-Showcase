import { getDistance, convertDistance } from "geolib";

let initiateGeoLocationWatcher = (
    store_latitude,
    store_longitude,
    order_range,
    watcherFunction = null
) => {
    let data = false;

    // console.log("lat long", store_latitude, store_longitude);

    return new Promise(function(resolve, reject) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const getCoordsData = setCurrentPosition(
                    position,
                    store_latitude,
                    store_longitude
                );

                if (watcherFunction) watcherFunction(getCoordsData);
                // console.log("getCoordsData", getCoordsData);

                if (order_range < getCoordsData?.metersAwayFromStore) {
                    // reactNotification.addNotification({
                    //     message: `You are outside the order range. You have to be ${this.props.store?.order_range}meter within to start your order`,
                    //     level: "error"
                    // });
                    // reject(
                    //     `You are outside the order range. You have to be ${order_range} meter within to start your order`
                    // );
                }

                // data = getCoordsData;

                resolve(getCoordsData);

                // console.log("data", data);
            },
            error => {
                const getError = positionError(error);
                reject(getError);
            },
            {
                enableHighAccuracy: false,
                timeout: 15000,
                maximumAge: 0
            }
        );
    });

    // return data;
};

// let geoLocationWatcher = (store_latitude, store_longitude) => {
//     return navigator.geolocation.watchPosition(
//         position => {
//             const emnei = setCurrentPosition(
//                 position,
//                 store_latitude,
//                 store_longitude
//             );
//         },
//         error => {
//             const error = positionError(error);
//             reject(error);
//         },
//         {
//             enableHighAccuracy: false,
//             timeout: 15000,
//             maximumAge: 0
//         }
//     );
// };

let setCurrentPosition = (position, store_latitude, store_longitude) => {
    // console.log("store_latitude", store_latitude);
    // console.log("store_longitude", store_longitude);
    // console.log("position", position);

    // return position;

    const distance = getDistance(
        {
            latitude: position?.coords?.latitude,
            longitude: position?.coords?.longitude
        },
        {
            latitude: parseFloat(store_latitude),
            longitude: parseFloat(store_longitude)
        }
    );
    const getConvertedDistance = convertDistance(distance, "m");

    // console.log("first", distance, getConvertedDistance);

    if (!distance) {
        // console.error("Can't get distance");
        return;
    }

    let data = {
        customerCoords: position?.coords,
        metersAwayFromStore: getConvertedDistance
    };

    // console.log("data", data);

    return data;
};

let positionError = error => {
    // const reactNotification = this.notificationSystem.current;
    let msg = "Please turn on location on your device.";

    switch (error.code) {
        case error.PERMISSION_DENIED:
            // console.error("User denied the request for Geolocation.");

            // reactNotification.addNotification({
            //     message: "User denied the request for Geolocation.",
            //     level: "error"
            // });
            // setTimeout(() => {
            //     this.geoLocation();
            // }, miscVariables.retryGeoLocation);
            msg = "Please turn on location on your device.";
            break;

        case error.POSITION_UNAVAILABLE:
            // console.error("Location information is unavailable.");

            // reactNotification.addNotification({
            //     message: "Location information is unavailable.",
            //     level: "error"
            // });
            // setTimeout(() => {
            //     this.geoLocation();
            // }, miscVariables.retryGeoLocation);
            msg = "Location information is unavailable.";
            break;

        case error.TIMEOUT:
            // console.error("The request to get user location timed out.");

            // reactNotification.addNotification({
            //     message: "The request to get user location timed out.",
            //     level: "error"
            // });
            // setTimeout(() => {
            //     this.geoLocation();
            // }, miscVariables.retryGeoLocation);
            msg = "The request to get user location timed out.";
            break;

        case error.UNKNOWN_ERROR:
            // console.error("An unknown error occurred.");

            // reactNotification.addNotification({
            //     message: "An unknown error occurred.",
            //     level: "error"
            // });
            // setTimeout(() => {
            //     this.geoLocation();
            // }, miscVariables.retryGeoLocation);
            msg = "An unknown error occurred.";
            break;
    }

    return msg;
};

export { initiateGeoLocationWatcher, setCurrentPosition, positionError };
