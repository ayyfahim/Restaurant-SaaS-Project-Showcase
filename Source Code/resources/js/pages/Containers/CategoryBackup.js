import React from "react";
import ReactDOM from "react-dom";
import ROUTE from "../../config/route";
import domain from "../../config/api/domain";

const Category = props => {
    return (
        <div>
            <div class="row m-0">
                {props.data.map(data => (
                    <div class="col-4 pl-0 pr-1 py-1" id="">
                        <a
                            href={`${ROUTE.STORE.INDEX.PAGES.DETAILED.PATH}/${props.storeId}/category/details/${data.id}`}
                        >
                            <div
                                class="shadow-sm rounded text-center  px-1 py-2 c-it category-1
                    "
                            >
                                <img
                                    style={{ objectFit: "cover" }}
                                    src={`${domain.url}/${data.image_url}`}
                                    class="img-fluid px-2 rounded-circle"
                                />
                                <p class="m-0 pt-2 text-muted text-center">{`${data.name}`}</p>
                            </div>
                        </a>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default Category;
