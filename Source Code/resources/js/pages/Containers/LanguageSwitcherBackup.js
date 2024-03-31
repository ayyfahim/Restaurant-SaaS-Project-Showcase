import React, { useEffect, useState } from "react";
import ReactDOM from "react-dom";
import {
    fetchTranslation,
    fetchAllTranslation
} from "../../actions/translationAction";
import domain from "../../config/api/domain";
import { connect } from "react-redux";
const LanguageSwitcher = props => {
    let { all_translation, active } = props;
    const change_language = id => {
        props.fetchTranslation({ language_id: id });
        localStorage.setItem("active_language_id", id);
    };
    return (
        <div class="col-6">
            <select
                class="form-control red-bg1"
                value={active}
                onChange={e => change_language(e.target.value)}
                name="selected_language"
                data-width="fit"
            >
                {all_translation &&
                    all_translation.map(data => (
                        <option value={data.id}>{data.language_name}</option>
                    ))}
            </select>
        </div>
    );
};

const mapSateToProps = state => ({});
export default connect(mapSateToProps, {
    fetchTranslation,
    fetchAllTranslation
})(LanguageSwitcher);
