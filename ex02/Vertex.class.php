<?php
	require_once 'Color.class.php';

	class Vertex
	{
		private $_x;
		private $_y;
		private $_z;
		private $_w = 1.0;
		private $_color;
		static $verbose = False;

		function __construct(array $args)
		{
			if (isset($args["w"]))
				$this->_w = $args["w"];
			if (isset($args["color"]))
				$this->_color = $args["color"];
			else
				$this->_color = new Color(array ("red" => 255, "green" => 255, "blue" => 255));
			if (isset($args["x"]) && isset($args["y"]) && isset($args["z"]))
			{
				$this->_x = $args["x"];
				$this->_y = $args["y"];
				$this->_z = $args["z"];
			}
			if (self::$verbose)
				print ($this." constructed\n");
		}

		function __destruct()
		{
			if (self::$verbose)
				print ($this." destructed\n");
		}

		function __toString()
		{
			if(self::$verbose)
			{
				return (sprintf("Vertex( x: %.2f, y: %.2f, z: %.2f, w: %.2f, $this->_color )",
								$this->_x, $this->_y, $this->_z, $this->_w));
			}			
			else
			{
				return (sprintf("Vertex( x: %.2f, y: %.2f, z: %.2f, w: %.2f )",
								$this->_x, $this->_y, $this->_z, $this->_w));
			}
		}

		static function doc()
		{
			$file_name = "Vertex.doc.txt";
			if (file_exists($file_name))
				return (file_get_contents($file_name));
			else
				return ("Doc file doesn't exist\n");
		}

		function getX()
		{
			return $this->_x;
		}

		function getY()
		{
			return $this->_y;
		}

		function getZ()
		{
			return $this->_z;
		}
	}
?>
