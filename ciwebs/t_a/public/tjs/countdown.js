define(["jquery"], function(e) {
	function t(e, t) {
		var n = this;
		this.timerId = null, this._timer = e, this.timer = e, this.update = function(e) {}, this.done = function() {}, this.start = function() {
			return n.timerId != null && clearInterval(n.timerId), n.timerId = setInterval(function() {
				--n.timer, n.timer >= 0 ? n.update.call(n, n.timer) : (n.done.call(n), n.stop())
			}, 1e3), n
		}, this.stop = function() {
			return clearInterval(n.timerId), n.timerId = null, n
		}, this.restart = function() {
			n.start(), n.timer = n._timer
		}
	}
	return t
});