import React from 'react';
import { NavLink, Route } from 'react-router-dom';
import ROUTE from '../../config/route'
class SideBar extends React.Component {
    state = {
        store_id:null
    }
    componentWillMount(){

        this.setState({store_id:localStorage.getItem('storeId')})
    }
render(){

    const {active} = this.props;
    const {store_id} =this.state;
        return (
            <div class="sidebar">



                <div class="list-group main-menu my-4">
                    <a href={`${ROUTE.STORE.INDEX.PAGES.VIEW.PATH}/${store_id}`} className={`list-group-item list-group-item-action ${active == "Home" ? "active" : null}`}>
                        <i class="material-icons">home
                        </i>Home
                    </a>
                    <a href={`${ROUTE.ACCOUNT.ORDERS.PAGES.VIEW.PATH}`} class={`list-group-item list-group-item-action ${active == "Orders" ? "active" : null}`}><i class="material-icons">insert_emoticon</i>Orders</a>
                </div>
            </div>



        );
    }
}

export default SideBar;


