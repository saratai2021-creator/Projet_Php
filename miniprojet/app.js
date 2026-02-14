import { Header } from "./components/header.js";
import { Routes , Route, BrowserRouter } from "react-router-dom";
import { ListeFilms } from "./components/listFilms.js";
import { AjoutModifier } from "./components/AjoutUpdate.js";
import { SupprimerFilm } from "./components/supprimer.js";
import { ChargeApi } from "./components/chargeApi.jsx";
// import '../index.css'; 
// import 'bootstrap/dist/css/bootstrap.min.css';
// import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import { Provider } from "react-redux";

import { store } from "./store/storeFilms";
export function App(){
    return(
        <Provider store={store}>
            <BrowserRouter>
        <div className="container">
                     <Header/>
         <Routes>
            <Route 
            element={<ListeFilms/>}
            path="/">
            </Route>
            <Route 
            element={<AjoutModifier/>}
            path="/ajout_mody">
            </Route>
            <Route 
            element={<SupprimerFilm/>}
            path="/delete">
            </Route>
            <Route 
            element={<AjoutModifier/>}
            path="/ajout_mody/:Id">
            </Route>
              <Route 
            element={<ChargeApi/>}
            path="/api">
            </Route>
            </Routes>  
        </div>
       </BrowserRouter> </Provider>
    )
}