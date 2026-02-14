import { Link } from "react-router-dom"
 export function Header(){
    return(
        <header className="mx-2 my-3 d-flex bg-primary py-3 px-2" width="95%">
            <Link
            style={{textDecoration:"none"}}
            className="text-light fw-bolder fs-3 mx-5"
            to="/">
           Liste Des Livres
            </Link>
            <Link
            style={{textDecoration:"none"}}
            className="text-light fw-bolder fs-3 mx-5"
            to="/ajout_mody">
           Ajouter
            </Link>
            <Link
            style={{textDecoration:"none"}}
            className="text-light fw-bolder fs-3 mx-5"
            to="/delete">
           Supprimer
            </Link>
            <Link
            style={{textDecoration:"none"}}
            className="text-light fw-bolder fs-3 mx-5"
            to="/api">
           Charge API 
            </Link>
        </header>
    )
 }