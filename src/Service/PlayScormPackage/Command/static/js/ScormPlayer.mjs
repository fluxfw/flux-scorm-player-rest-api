import "./scorm-again/scorm-again.min.js";

const {type, api_settings} = JSON.parse(atob(new URL(import.meta.url).hash.substring(1)));

let api;
switch (type) {
    case "aaic":
        api = globalThis.API = new AICC(api_settings);
        break;

    case "1_2":
        api = globalThis.API = new Scorm12API(api_settings);
        break;

    case "2004":
        api = globalThis.API_1484_11 = new Scorm2004API(api_settings);
        break;

    default:
        throw new Error(`Unknown scorm type ${type}`);
}

api.on("SetValue.cmi.*", (CMIElement, value) => {
    api.storeData(false);
});

let unloaded = false;
globalThis.onbeforeunload = globalThis.onunload = () => {
    if (!unloaded && !api.isTerminated()) {
        unloaded = true;
        api.SetValue("cmi.exit", "suspend");
        api.Commit("");
        api.Terminate("");
    }

    return false;
}

api.loadFromJSON(await (await fetch(api_settings.lmsCommitUrl)).json(), "");

export {api};
